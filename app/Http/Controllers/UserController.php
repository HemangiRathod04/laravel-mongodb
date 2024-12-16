<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     */
    // public function list(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = User::with('country');
           
    
    //         if ($request->filled('country_id')) {
    //             $query->where('country_id', $request->country_id);
    //         }
    
    //         if ($request->filled('status')) {
    //             $query->where('status', $request->status);
    //         }
    //         // $data = $query;
    //         // dd($query);
    //         return DataTables::of($query)
    //         // ->addIndexColumn()
    //         // ->addColumn('checkbox', function($row) {
    //         //     return '<input type="checkbox" name="selected_ids[]" value="' . $row->id . '">';
    //         // })
    //         // ->addColumn('status', function($row) {
    //         //     return $row->status == 1 ? 'Active' : 'Inactive'; 
    //         // })
    //         // ->addColumn('action', function($row){
    //         //     $btn = '<a href="'.route('users.edit', $row->id).'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
    //         //     $btn .= '<a href="'.route('users.destroy', $row->id).'" class="delete btn btn-danger btn-sm ms-2"><i class="fas fa-trash-alt"></i></a>';
    //         //     return $btn;
    //         // })
    //         // ->addColumn('country_name', function($row) {
    //         //     return $row->country ? $row->country->name : 'N/A';
    //         // })
    //         // ->rawColumns(['checkbox', 'action'])
    //         ->make(true);
    //         dd(12);
    //     }
    //             $countries = Country::all();
    //             return view('welcome', compact('countries'));
    // }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('country');

            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            if ($request->filled('status')) {
                $status = (int) $request->status;
                $query->where('status', $status);
            }

        $columnIndex = $request->input('order.0.column');
        $columnName = $request->input('columns.' . $columnIndex . '.data');
        $sortDirection = $request->input('order.0.dir', 'asc');

        if (in_array($columnName, ['first_name', 'last_name', 'email', 'phone', 'gender', 'date_of_birth', 'status', 'address_1', 'address_2', 'country_name'])) {
            $query->orderBy($columnName, $sortDirection);
        }

        $searchValue = $request->input('search.value');
        // dd($searchValue);
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('first_name', 'like', "%{$searchValue}%")
                  ->orWhere('last_name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhere('gender', 'like', "%{$searchValue}%")
                  ->orWhere('date_of_birth', 'like', "%{$searchValue}%")
                  ->orWhere('address_1', 'like', "%{$searchValue}%")
                  ->orWhere('address_2', 'like', "%{$searchValue}%")
                  ->orWhereHas('country', function($q) use ($searchValue) {
                      $q->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

            $totalRecords = $query->count();

            $data = $query->skip($request->start)->take($request->length)->get();
            $formattedData = $data->map(function($row) {
                \Log::info('Raw Phone Data:', ['phone' => $row->phone]);
                return [
                    'id' => $row->_id,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name,
                    'email' => $row->email,
                    'phone' => $row->phone,
                    'gender' => $row->gender,
                    'date_of_birth' => $row->date_of_birth->format('Y-m-d'),
                    'status' => $row->status ? 'Active' : 'Inactive',
                    'address_1' => $row->address_1,
                    'address_2' => $row->address_2,
                    'country_name' => $row->country ? $row->country->name : 'N/A',
                    'action' => '<a href="'.route('users.edit', $row->_id).'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a> ' .
                                '<a href="'.route('users.destroy', $row->_id).'" class="delete btn btn-danger btn-sm ms-2"><i class="fas fa-trash-alt"></i></a>',
                    'checkbox' => '<input type="checkbox" name="selected_ids[]" value="' . $row->_id . '">'
                ];
            });

            // dd($formattedData);
            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $formattedData
            ]);

        }
        $countries = Country::all();
        return view('welcome', compact('countries'));
    }



    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $countries=Country::get();
        return view('users.add',['countries'=> $countries])->with('success', 'User created successfuly.');
    }

    /**
     * Store a new User
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => [
                'required',
                 'max:20',
                 'regex:/^[\d\+\-\(\)\s]*$/'
                ],
            'gender' => 'required',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'status' => 'required|in:0,1',
            'address_1' => 'required|max:255',
            'address_2' => 'nullable|max:255',
            'country' => 'required',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->status = (int)$request->status;
        $user->address_1 = $request->address_1;
        $user->address_2 = $request->address_2; 
        $user->country_id = $request->country;
        $user->save();
        return redirect()->route('users.list')->with('success', 'User created successfully');
        
    }

    /**
     * Edit user
     */
    public function edit(string $id)
    {
        $user=User::find($id);
        $countries=Country::get();
        return view('users.add',['user'=> $user,'countries'=>$countries]);
    }

    /**
     * Update the User
     */
    public function update(Request $request, string $id)
    {
        $userId = (string) $id;
        $user = User::find($userId);
        $isEmailChanged = $request->input('email') !== $user->email;

        $request->validate([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => [
                'required',
                'email',
                $isEmailChanged ? Rule::unique('users', 'email')->ignore($userId) : 'nullable'

            ],
            'password' => 'nullable|min:6',
            'phone' => [
                'required',
                'max:20',
                'regex:/^[\d\+\-\(\)\s]*$/'
                ],
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'status' => 'required|in:0,1',
            'address_1' => 'required|max:255',
            'address_2' => 'nullable|max:255',
            'country' => 'required',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->status = (int)$request->status;
        $user->address_1 = $request->address_1;
        $user->address_2 = $request->address_2;
        $user->country_id = $request->country;
        $user->save();

        return redirect()->route('users.list')->with('success', 'User updated successfully');
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user=User::find($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    /**
     * Delete multiple users.
     */
    public function deleteSelectedUserIds(Request $request)
    {
        $selectedUserIds = $request->input('selectedUserIds', []);
        if (empty($selectedUserIds)) {
            return response()->json(['error' => 'No Users selected for deletion.'], 422);
        }
        try {
            User::destroy($selectedUserIds);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete selected Users.'], 500);
        }
    }

    /**
     * Filter data from listing.
     */
    public function filter(Request $request)
    {
        $countryId = $request->input('country_id');
        $status = $request->input('status');

        $query = User::with('country');

        if ($countryId && $countryId != 'Select Country') {
            $query->where('country_id', $countryId);
        }

        if ($status !== 'Select Status') {
            $query->where('status', $status);
        }

        $users = $query->get();

        return response()->json($users);
    }
}
