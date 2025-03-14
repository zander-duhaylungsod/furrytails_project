<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Boarding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Storage;

class AdminUsersController extends Controller
{
    public function index()
    {
        // Get total users count
        $totalUsers = User::count();
        
        // Get active users (assuming all users are active since there's no is_active field)
        $activeUsers = $totalUsers;
        
        // Get new users in the last 30 days
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        return view('admin.users', compact('totalUsers', 'activeUsers', 'newUsers'));
    }

    /**
     * Get a list of all users for dropdown selection
     */
    public function getUsersList()
    {
        try {
            $users = User::select('userID', 'firstName', 'lastName')
                ->orderBy('firstName')
                ->orderBy('lastName')
                ->get();
                
            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Error fetching users list: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
    
    /**
     * Get all pets belonging to a specific user
     */
    public function getUserPets($userId) 
    {
        try {
            $pets = \App\Models\Pet::where('userID', $userId)->get();
            return response()->json($pets);
        } catch (\Exception $e) {
            \Log::error('Error fetching pets: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function storeUser(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'firstName' => 'required|string|max:100',
                'lastName' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|unique:users,username|max:50',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:8',
                'role' => 'required|in:user,admin',
                'userImage' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Create new user
            $user = new User();
            $user->firstName = $validated['firstName'];
            $user->lastName = $validated['lastName'];
            $user->email = $validated['email'];
            $user->username = $validated['username'];
            $user->phone = $validated['phone'];
            $user->password = Hash::make($validated['password']);
            $user->role = $validated['role'];
            $user->save();

            // Handle image upload if present
            if ($request->hasFile('userImage')) {
                // Log for debugging
                \Log::info('User image upload started', [
                    'userID' => $user->userID,
                    'file' => $request->file('userImage')
                ]);
                
                // Get file extension
                $extension = $request->file('userImage')->getClientOriginalExtension();
                
                // Create custom filename with userID
                $filename = 'user_' . $user->userID . '_' . time() . '.' . $extension;
                
                // Store with custom filename
                $imagePath = $request->file('userImage')->storeAs(
                    'userImages', 
                    $filename, 
                    'public'
                );
                
                // Log success
                \Log::info('User image saved', [
                    'path' => $imagePath
                ]);
                
                $user->userImage = $imagePath;
                $user->save(); // Save again with the image path
            } else if ($request->has('cropped_image')) {
                // Handle base64 encoded image from cropper
                \Log::info('Processing cropped image');
                
                try {
                    $croppedImage = $request->input('cropped_image');
                    
                    // Remove header information from base64 string
                    $image_parts = explode(";base64,", $croppedImage);
                    $image_base64 = isset($image_parts[1]) ? $image_parts[1] : $croppedImage;
                    
                    // Create image from base64 string
                    $imageData = base64_decode($image_base64);
                    
                    // Create custom filename with userID
                    $filename = 'user_' . $user->userID . '_' . time() . '.jpg';
                    
                    // Path to save the image
                    $imagePath = 'userImages/' . $filename;
                    $fullPath = storage_path('app/public/' . $imagePath);
                    
                    // Ensure the directory exists
                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }
                    
                    // Save the image
                    file_put_contents($fullPath, $imageData);
                    
                    // Update user with image path
                    $user->userImage = $imagePath;
                    $user->save();
                    
                    \Log::info('Cropped image saved', ['path' => $imagePath]);
                } catch (\Exception $e) {
                    \Log::error('Error saving cropped image: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user details for modal view
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Create empty pets array - we'll check your actual DB structure
            $pets = []; 
            
            // Check database structure and use proper column names
            // This is a simplified approach - check your actual DB columns
            $appointmentsCount = 0;
            $boardingsCount = 0;
            $petsCount = 0;
            
            try {
                // Use DB facade to directly check table structure
                $pets = \DB::table('pets')
                    ->orWhere('userID', $id)
                    ->get();
                
                    // Fix pet image paths
            foreach ($pets as $pet) {
                if (isset($pet->petImage)) {
                    // Remove any incorrect paths that might be stored in the database
                    $imagePath = preg_replace('/^dashboard\/furrytails_project\/public\//', '', $pet->petImage);
                    
                    // Check if path already starts with "storage/"
                    if (strpos($imagePath, 'storage/') === 0) {
                        $pet->petImage = asset($imagePath);
                    } 
                    // Check if path has admin/seed pattern
                    else if (strpos($imagePath, 'admin/seed/') === 0) {
                        $pet->petImage = asset('storage/' . $imagePath);
                    }
                    // For any other path format
                    else {
                        $pet->petImage = asset('storage/' . $imagePath);
                    }
                    
                    // Debug the image path transformation
                    \Log::info('Pet image transformed: ' . $pet->petImage);
                }
            }
            
                $petsCount = count($pets);
                
                // Get all pet IDs belonging to this user first
                $petIDs = \DB::table('pets')
                ->where('userID', $id)
                ->pluck('petID')
                ->toArray();

                // Count appointments for these pets
                $appointmentsCount = empty($petIDs) ? 0 : \DB::table('appointments')
                ->whereIn('petID', $petIDs)
                ->count();

                // Count boardings for these pets
                $boardingsCount = empty($petIDs) ? 0 : \DB::table('boardings')
                ->whereIn('petID', $petIDs)
                ->count();
                
            } catch (\Exception $e) {
                // Just continue with zeros if this fails
                \Log::warning('Error fetching related data: ' . $e->getMessage());
            }
            
            // Add stats to user object
            $user->appointmentsCount = $appointmentsCount;
            $user->boardingsCount = $boardingsCount;
            $user->petsCount = $petsCount;
            
            // Format profile image URL if exists (using flexible column naming)
            $imageColumn = null;
            if (isset($user->profileImage)) {
                $imageColumn = 'profileImage';
            } else if (isset($user->userImage)) {
                $imageColumn = 'userImage';
            } else if (isset($user->image)) {
                $imageColumn = 'image';
            } else if (isset($user->profile_image)) {
                $imageColumn = 'profile_image';
            }
            
            if ($imageColumn && $user->$imageColumn) {
                $user->profileImage = asset('storage/' . $user->$imageColumn);
            } else {
                $user->profileImage = null;
            }
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'pets' => $pets
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user details
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate the request
            $rules = [
                'firstName' => 'required|string|max:100',
                'lastName' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $id . ',userID',
                'username' => 'required|string|unique:users,username,' . $id . ',userID',
                'phone' => 'required|string|max:20',
                'role' => 'required|in:user,admin',
                'userImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            
            // Add password validation only if it's provided
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:8';
            }
            
            $validated = $request->validate($rules);
            
            // Update user data
            $user->firstName = $validated['firstName'];
            $user->lastName = $validated['lastName'];
            $user->email = $validated['email'];
            $user->username = $validated['username'];
            $user->phone = $validated['phone'];
            $user->role = $validated['role'];
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // Handle image upload if present
            if ($request->hasFile('userImage')) {
                // Delete old image if it exists and isn't the default
                if ($user->userImage && $user->userImage != 'userImages/default.png') {
                    Storage::disk('public')->delete($user->userImage);
                }
                
                // Get file extension
                $extension = $request->file('userImage')->getClientOriginalExtension();
                
                // Create custom filename with userID
                $filename = 'user_' . $user->userID . '_' . time() . '.' . $extension;
                
                // Store with custom filename
                $imagePath = $request->file('userImage')->storeAs(
                    'userImages', 
                    $filename, 
                    'public'
                );
                
                $user->userImage = $imagePath;
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user and all related data
     */
    public function destroy($id)
    {
        try {
            \DB::beginTransaction();
            
            // Find the user
            $user = User::findOrFail($id);
            
            // Safety check - don't allow deleting the last admin
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete the last admin user.'
                    ], 403);
                }
            }
            
            // Get all pets belonging to this user
            $pets = Pet::where('userID', $id)->get();
            
            // Delete related data for each pet
            foreach ($pets as $pet) {
                // Delete appointments for this pet
                Appointment::where('petID', $pet->petID)->delete();
                
                // Delete boardings for this pet
                Boarding::where('petID', $pet->petID)->delete();
                
                // Delete pet's image if exists
                if ($pet->petImage && $pet->petImage != 'petImages/default.png') {
                    Storage::disk('public')->delete($pet->petImage);
                }
                
                // Delete the pet
                $pet->delete();
            }
            
            // Delete payments linked to this user
            \DB::table('payments')->where('userID', $id)->delete();
            
            // Delete user's profile image if exists and not default
            if ($user->userImage && $user->userImage != 'userImages/default.png') {
                Storage::disk('public')->delete($user->userImage);
            }
            
            // Finally delete the user
            $user->delete();
            
            \DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error deleting user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}