<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Folder;
use App\Models\PurchaseDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{

    public function index(Request $request)
    {


        $cartCount = Cart::where("user_id", operator: Auth::user()->id)->count();
        $loggedInDevices = DB::table(table: 'sessions')->where("user_id", Auth::user()->id)->count();

        $purchasesList = PurchaseDetail::with(['book'])
            ->whereHas('purchase', function ($query) {
                $query->where('is_paid', 1);
            })
            ->where('user_id', Auth::user()->id)
            ->orderBy('order', 'asc')
            ->get()
            ->unique('book_id')
            ->map(function ($purchase) {
                // Get total original quantity purchased for this book
                $totalQuantity = PurchaseDetail::where('book_id', $purchase->book_id)
                    ->where('user_id', Auth::user()->id)
                    ->whereHas('purchase', function ($query) {
                        $query->where('is_paid', 1);
                    })
                    ->sum(DB::raw('COALESCE(original_quantity, quantity)'));
                
                // Get total remaining quantity for this book
                $remainingQuantity = PurchaseDetail::where('book_id', $purchase->book_id)
                    ->where('user_id', Auth::user()->id)
                    ->whereHas('purchase', function ($query) {
                        $query->where('is_paid', 1);
                    })
                    ->sum('quantity');
                
                return [
                    'id' => $purchase->book->id,
                    'src' => $purchase->book->images,
                    'name' => $purchase->book->name,
                    'total_quantity' => $totalQuantity,
                    'remaining_quantity' => $remainingQuantity,
                ];
            })
            ->values();

        return view('library', compact("cartCount", "purchasesList", "loggedInDevices"));
    }

    public function getBooksByFolder($name)
    {
        $userId = Auth::id();

        // Find folder by name and user
        $folder = Folder::where('name', $name)
            ->where('user_id', $userId)
            ->first();

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }

        // Get books inside the folder sorted by 'order'
        $books = PurchaseDetail::with('book')
            ->where('folder_id', $folder->id)
            ->where('user_id', $userId)
            ->whereHas('purchase', function ($q) {
                $q->where('is_paid', 1);
            })
            ->orderByRaw('COALESCE(folder_id, 0) ASC') // group nulls first
            ->orderBy('order', 'asc')
            ->get()
            ->unique('book_id')
            ->map(function ($purchase) use ($userId) {
                // Get total original quantity purchased for this book
                $totalQuantity = PurchaseDetail::where('book_id', $purchase->book_id)
                    ->where('user_id', $userId)
                    ->whereHas('purchase', function ($query) {
                        $query->where('is_paid', 1);
                    })
                    ->sum(DB::raw('COALESCE(original_quantity, quantity)'));
                
                // Get total remaining quantity for this book
                $remainingQuantity = PurchaseDetail::where('book_id', $purchase->book_id)
                    ->where('user_id', $userId)
                    ->whereHas('purchase', function ($query) {
                        $query->where('is_paid', 1);
                    })
                    ->sum('quantity');
                
                return [
                    'id' => $purchase->book->id,
                    'src' => $purchase->book->images,
                    'name' => $purchase->book->name,
                    'folder' => $purchase->folder->name,
                    "order" => $purchase->order,
                    'total_quantity' => $totalQuantity,
                    'remaining_quantity' => $remainingQuantity,
                ];
            })
            ->values();

        return response()->json($books);
    }

}