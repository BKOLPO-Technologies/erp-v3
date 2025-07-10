<?php
namespace App\Http\Controllers\Accounts;
use Exception;
use Carbon\Carbon;
use App\Models\Accounts\Sale;
use App\Models\Accounts\Unit;
use App\Models\Accounts\Client;
use App\Models\Accounts\Product;
use App\Models\Accounts\Project;
use App\Models\Accounts\Category;
use App\Models\Accounts\Purchase;
use App\Models\Accounts\ProjectItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Accounts\JournalVoucher;
use App\Traits\ProjectSalesTrait;
use App\Traits\TrialBalanceTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Accounts\JournalVoucherDetail;
use Illuminate\Database\QueryException;

class ProjectController extends Controller
{
    // Include the trait
    use ProjectSalesTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Project List';
    
        $projects = Project::latest() ->with('client')->get();

        return view('Accounts.inventory.project.index', compact('pageTitle', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Project Create';

        $clients = Client::latest()->get();

        // // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $referance_no = 'BKOLPO-'. $randomNumber;

        // Get current timestamp in 'dmyHis' format (day, month, year)
        // $randomNumber = rand(100000, 999999);
        // $fullDate = now()->format('d/m/y');

        // // Combine the timestamp, random number, and full date
        // $referance_no = 'BCL-PR-'.$fullDate.' - '.$randomNumber;
        $units = Unit::where('status',1)->latest()->get();
        $categories = Category::all();
        $products = Product::where('status',1)->latest()->get();

        $companyInfo = get_company(); // Fetch company info

        // Get the current date and month
        $currentMonth = now()->format('m'); // Current month (01-12)
        $currentYear = now()->format('y'); // Current year (yy)

        // Generate a random number for the current insert
        $randomNumber = rand(100000, 999999);

        // Get the last reference number for the current month
        $lastReference = Project::whereRaw('MONTH(created_at) = ?', [$currentMonth]) // Filter by the current month
        ->orderBy('created_at', 'desc') // Order by the latest created entry
        ->first(); // Get the latest entry

        // Increment the reference number for this month
        if ($lastReference) {
            // Extract the incremental part from the last reference number
            preg_match('/(\d{3})$/', $lastReference->reference_no, $matches); // Assuming the last part is always 3 digits (001, 002, etc.)
            $increment = (int)$matches[0] + 1; // Increment the number
        } else {
            // If no reference exists for the current month, start from 001
            $increment = 1;
        }

        // Format the increment to be always 3 digits (e.g., 001, 002, 003)
        $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);


        // Remove the hyphen from fiscal year (e.g., "24-25" becomes "2425")
        $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);

        // Combine fiscal year, current month, and the incremental number to generate the reference number
        $referance_no = 'BCL-PR-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;
      
        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;
        $productCode = 'PRD' . strtoupper(Str::random(5));

        return view('Accounts.inventory.project.create',compact('pageTitle','clients','referance_no','units','products','vat','tax', 'categories', 'productCode')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction(); // Start a database transaction

        try {
            // Validate project data
            $request->validate([
                'project_name' => 'required|string|max:255',
                'project_location' => 'required|string|max:255',
                'project_coordinator' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
                'reference_no' => 'required|string|unique:projects,reference_no',
                'schedule_date' => 'nullable|date',
                'total_discount' => 'nullable|numeric|min:0',
                'total_subtotal' => 'nullable|numeric|min:0',
                'transport_cost' => 'nullable|numeric|min:0',
                'carrying_charge' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'grand_total' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'project_type' => 'required|in:ongoing,Running,upcoming,completed',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'items' => 'required|array',
                'items.*' => 'required|string|max:255',
                'order_unit' => 'required|array',
                'order_unit.*' => 'required|max:255',
                'unit_price' => 'required|array',
                'unit_price.*' => 'required|numeric|min:0',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
                // 'subtotal' => 'required|array',
                // 'subtotal.*' => 'required|numeric|min:0',
                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',
                'total' => 'required|array',
                'total.*' => 'required|numeric|min:0',
            ]);

           

            $tax = $request->include_tax ? $request->tax : 0; 
            $vat = $request->include_vat ? $request->vat : 0; 
    
            // Store the project in the database
            $project = Project::create([
                'project_name' => $request->project_name,
                'project_location' => $request->project_location,
                'project_coordinator' => $request->project_coordinator,
                'client_id' => $request->client_id,
                'reference_no' => $request->reference_no,
                'schedule_date' => $request->schedule_date,
                'total_discount' => $request->total_discount ?? 0,
                'total_netamount' => $request->total_netamount ?? 0,
                'subtotal' => $request->total_subtotal ?? 0,
                'transport_cost' => $request->transport_cost ?? 0,
                'carrying_charge' => $request->carrying_charge ?? 0,
                'vat' => $vat,
                'vat_amount' => $request->vat_amount,
                'tax' => $tax,
                'tax_amount' => $request->tax_amount,
                'grand_total' => $request->grand_total ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => 'pending',
                'project_type' => $request->project_type,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
            ]);

         
    
            // Store project items
            foreach ($request->items as $index => $item) {
                ProjectItem::create([
                    'project_id' => $project->id,
                    'items' => $item,
                    'unit_id' => $request->order_unit[$index],
                    'unit_price' => $request->unit_price[$index],
                    'quantity' => $request->quantity[$index],
                    'subtotal' => $request->subtotal[$index] ?? 0,
                    'discount' => $request->discount[$index] ?? 0,
                    'total' => $request->total[$index],
                    'items_description' => $request->items_description[$index] ?? '',
                ]);
            }

    
            DB::commit(); // Commit transaction if everything is successful
    
            return redirect()->route('accounts.projects.index')->with('success', 'Project and items created successfully!');
    
        } catch (QueryException $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    
        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function AdminProductStore2(Request $request)
    {
        //dd($request->all());
        //Log::info('AdminProductStore2 called with request:', $request->all());

        DB::beginTransaction(); // Start a database transaction

        try {
            // Validate the incoming request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required',
                'unit_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            //Log::info('AdminProductStore2 2nd time:', $request->all());

            //$productCode = 'PRD' . strtoupper(Str::random(5));

            // Store the product with the validated data
            $product =  Product::create([
                'name' => $request->name,
                'product_code' => $request->code,
                'price' => $request->price ?? 0, // Store null if not provided
                'description' => $request->description ?? null, // Store null if not provided
                'status' => $request->status ?? 1, // Default to active if not provided
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'group_name' => $request->group_name,
            ]);

            //Log::info('AdminProductStore2 3rd time:', $product->toArray());

            // if ($request->hasFile('image')) {
            //     @unlink(public_path('upload/inventory/products' . $product->image)); // Delete old logo
            //     $file = $request->file('image');
            //     $filename = date('YmdHi') . $file->getClientOriginalName();
            //     $file->move(public_path('upload/inventory/products'), $filename);
            //     $product->image = $filename;
            // }
            
            $product->save();

            //Log::info('Product:', $product->toArray());

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Product created successfully!',
                'product' => $product,
            ]);
    
        } catch (QueryException $e) {
            DB::rollBack();
            
            return response()->json([
                'success'  => false,
                'message'  => 'Database error: ' . $e->getMessage(),
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //dd($id);
        $pageTitle = 'Project Details';

        $project = Project::where('id', $id)
            ->with(['client', 'items']) 
            ->first();

        $companyInfo = get_company();
        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;

        return view('Accounts.inventory.project.view',compact('pageTitle', 'project','vat','tax'));
    }

    /**
     * projectsSales.
     */
    public function projectsSales(Request $request, $id)
    {
        //dd($id);
        $pageTitle = 'Project Sale Details';

        // $project = Project::where('id', $id)
        //     ->with(['client', 'items', 'sales', 'purchases', 'receipts']) 
        //     ->first();

        //dd($project);

        // Check if the request has date filters
        if ($request->has('from_date') && $request->has('to_date')) {
            // Use the provided date range
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
        } else {
            // Default: Last 1 month from today
            $fromDate = now()->subMonth()->format('Y-m-d'); // Last 1 month
            $toDate = now()->format('Y-m-d'); // Today's date
        }
        // Fetch the trial balance data based on the date range
        //$trialBalances = $this->getTrialBalance($fromDate, $toDate);

        // // Calculate totals in backend
        // $totalAmount = $project->grand_total;
        // $paidAmount = $project->paid_amount;
        // $dueAmount = $project->grand_total-$project->paid_amount;

        // Get data using the trait method
        $data = $this->getProjectSalesData($id, $fromDate, $toDate);

        //dd($data);

        //return view('Accounts.inventory.project.sale',compact('pageTitle', 'project', 'fromDate', 'toDate', 'totalAmount', 'paidAmount','dueAmount'));

        // Pass data to the view
        return view('Accounts.inventory.project.sale', array_merge($data, compact('pageTitle', 'fromDate', 'toDate')));
    }

    /**
     * showDetails.
     */
    public function showDetails($purchaseId)
    {
        try {
            $purchase = Purchase::with('purchaseProducts') // Assuming 'purchaseProducts' is the relation name in your model
                ->findOrFail($purchaseId);

            //Log::info($purchase);

            $html = view('Accounts.inventory.project.purchaseDetails', compact('purchase'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching purchase details: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching purchase details. Please try again later.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Project Edit';

        $clients = Client::latest()->get();
        $categories = Category::all();

        $project = Project::where('id', $id)
            ->with(['client', 'items']) 
            ->first();

        $units = Unit::where('status',1)->latest()->get();
        $products = Product::where('status',1)->latest()->get();

        $companyInfo = get_company();
        
        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;

        return view('Accounts.inventory.project.edit',compact('pageTitle', 'clients', 'project','units','products','vat','tax', 'categories'));
    }

    public function AdminProductModal()
    {
        Log::info('Hello');
        $categories = Category::where('status', 1)->latest()->get();
        $units = Unit::where('status', 1)->latest()->get();
        $productCode = 'PRD' . strtoupper(Str::random(5));

        return view('Accounts.inventory.project.product_modal', compact('categories', 'units', 'productCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {

        //dd($request->all());
        DB::beginTransaction(); // Start a database transaction

        try {
            // Validate project data
            $request->validate([
                'project_name' => 'required|string|max:255',
                'project_location' => 'required|string|max:255',
                'project_coordinator' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
                'reference_no' => 'required|string|unique:projects,reference_no,' . $project->id,
                'schedule_date' => 'nullable|date',
                'total_discount' => 'nullable|numeric|min:0',
                'total_subtotal' => 'nullable|numeric|min:0',
                'transport_cost' => 'nullable|numeric|min:0',
                'carrying_charge' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'grand_total' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'project_type' => 'required|in:ongoing,Running,upcoming,completed',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'items' => 'required|array',
                'items.*' => 'required|string|max:255',
                'order_unit' => 'required|array',
                'order_unit.*' => 'required|max:255',
                'unit_price' => 'required|array',
                'unit_price.*' => 'required|numeric|min:0',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
                // 'subtotal' => 'required|array',
                // 'subtotal.*' => 'required|numeric|min:0',
                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',
                'total' => 'required|array',
                'total.*' => 'required|numeric|min:0',
                'item_ids' => 'nullable|array', // To track existing item IDs
                'item_ids.*' => 'nullable|integer|exists:project_items,id', 
            ]);

            $tax = $request->include_tax ? $request->tax : 0; 
            $vat = $request->include_vat ? $request->vat : 0; 

            //dd("h");
            // Update project details
            $project->update([
                'project_name' => $request->project_name,
                'project_location' => $request->project_location,
                'project_coordinator' => $request->project_coordinator,
                'client_id' => $request->client_id,
                'reference_no' => $request->reference_no,
                'schedule_date' => $request->schedule_date,
                'total_discount' => $request->total_discount ?? 0,
                'total_netamount' => $request->total_netamount ?? 0,
                'subtotal' => $request->total_subtotal ?? 0,
                'transport_cost' => $request->transport_cost ?? 0,
                'carrying_charge' => $request->carrying_charge ?? 0,
                'vat' => $vat,
                'vat_amount' => $request->vat_amount,
                'tax' => $tax,
                'tax_amount' => $request->tax_amount,
                'grand_total' => $request->grand_total ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'project_type' => $request->project_type,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
            ]);

            // Get existing project items
            $existingItems = $project->items()->pluck('id')->toArray();
            $incomingItemIds = $request->item_ids ?? []; // IDs of existing items from request

            // Identify items to be deleted
            $itemsToDelete = array_diff($existingItems, $incomingItemIds);
            ProjectItem::whereIn('id', $itemsToDelete)->delete(); // Delete removed items

            // Loop through request items to update or create
            foreach ($request->items as $index => $item) {
                if (!empty($incomingItemIds[$index])) {
                    // Update existing item
                    ProjectItem::where('id', $incomingItemIds[$index])->update([
                        'items' => $item,
                        'unit_id' => $request->order_unit[$index],
                        'unit_price' => $request->unit_price[$index],
                        'quantity' => $request->quantity[$index],
                        'subtotal' => $request->subtotal[$index] ?? 0,
                        'discount' => $request->discount[$index] ?? 0,
                        'total' => $request->total[$index],
                        'items_description' => $request->items_description[$index],
                    ]);
                } else {
                    // Create new item
                    ProjectItem::create([
                        'project_id' => $project->id,
                        'items' => $item,
                        'unit_id' => $request->order_unit[$index],
                        'unit_price' => $request->unit_price[$index],
                        'quantity' => $request->quantity[$index],
                        'subtotal' => $request->subtotal[$index] ?? 0,
                        'discount' => $request->discount[$index] ?? 0,
                        'total' => $request->total[$index],
                        'items_description' => $request->items_description[$index],
                    ]);
                }
            }

            DB::commit(); // Commit transaction if everything is successful

            return redirect()->route('accounts.projects.index')->with('success', 'Project updated successfully!');

        } catch (QueryException $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());

        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error

            // Log the error with detailed context
            Log::error('Project update failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'project_id' => $project->id,
            ]);
            
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction(); // Start a transaction

        try {
            // Find the project by ID
            $project = Project::findOrFail($id);

            // Delete related project items
            $project->items()->delete();

            // Delete the project itself
            $project->delete();

            DB::commit(); // Commit transaction if everything is successful

            return redirect()->route('accounts.projects.index')->with('success', 'Project deleted successfully!');
        
        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * getProjectDetails.
     */
    public function getProjectDetails(Request $request)
    {
        $sales = Sale::where('project_id', $request->project_id)->whereColumn('total', '>', 'paid_amount')->get();
    
        if ($sales->isNotEmpty()) {
            $total_amount = $sales->sum('total');
            $paid_amount = $sales->sum('paid_amount');
            $due_amount = $total_amount - $paid_amount;
    
            return response()->json([
                'success' => true,
                'total_amount' => $total_amount,
                'due_amount' => $due_amount,
                'sales' => $sales // Sending sales data
            ]);
        }
    
        return response()->json(['success' => false]);
    }    

}
