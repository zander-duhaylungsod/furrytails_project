<!-- Edit User Modal -->
<div id="editUser-modal" tabindex="-1" aria-hidden="true" class="tw-hidden tw-fixed tw-top-0 tw-left-0 tw-right-0 tw-z-50 tw-w-full tw-p-4 tw-overflow-x-hidden tw-overflow-y-auto md:tw-inset-0 tw-h-full tw-max-h-full tw-flex tw-items-center tw-justify-center tw-backdrop-blur-sm tw-bg-black/60">
    <div class="tw-relative tw-w-full tw-max-w-2xl tw-max-h-full tw-animate-modal-entry">
        <!-- Modal content -->
        <div class="tw-relative tw-bg-gray-800 tw-rounded-lg tw-shadow-sm tw-transform tw-transition-all">
            <!-- Modal header -->
            <div class="tw-flex tw-items-center tw-justify-between tw-p-4 md:tw-p-5 tw-border-b tw-rounded-t tw-border-gray-700">
                <h3 class="tw-text-lg tw-font-semibold tw-text-white">Edit User</h3>
                <button type="button" class="tw-text-gray-400 tw-bg-transparent tw-hover:tw-bg-gray-700 tw-hover:tw-text-white tw-rounded-lg tw-text-sm tw-w-8 tw-h-8 ms-auto tw-inline-flex tw-justify-center tw-items-center" data-modal-toggle="editUser-modal">
                    <svg class="tw-w-3 tw-h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <!-- Modal body -->
            <div class="tw-p-4 md:tw-p-5">
                <form id="editUserForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editUserID" name="userID">
                    
                    <!-- User Info Section -->
                    <div class="tw-flex tw-flex-col md:tw-flex-row tw-gap-6">
                        <!-- User Image Column -->
                        <div class="tw-flex tw-flex-col tw-items-center">
                            <div id="editUserImage" class="tw-h-40 tw-w-40 tw-rounded-full tw-bg-gray-700 tw-flex tw-items-center tw-justify-center tw-overflow-hidden">
                                <!-- Image will be set via JavaScript -->
                                <i class="fas fa-user tw-text-4xl tw-text-gray-500"></i>
                            </div>
                            <div class="tw-mt-3 tw-w-full">
                                <label for="userImageUpload" class="tw-block tw-w-full">
                                    <div class="tw-bg-gray-700 tw-text-sm tw-text-[#24CFF4] tw-px-4 tw-py-2 tw-rounded-lg tw-cursor-pointer hover:tw-bg-gray-600 tw-text-center">
                                        <i class="fas fa-camera tw-mr-2"></i> Change Photo
                                    </div>
                                </label>
                                <input type="file" id="userImageUpload" name="userImage" class="tw-hidden" accept="image/jpeg, image/png, image/jpg">
                            </div>
                        </div>
                        
                        <!-- User Details Column -->
                        <div class="tw-flex-1 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="firstName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label for="lastName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Email</label>
                                <input type="email" id="email-edit" name="email" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                            </div>
                            
                            <!-- Username -->
                            <div>
                                <label for="username" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Username</label>
                                <input type="text" id="username-edit" name="username" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Phone</label>
                                <input type="tel" id="phone-edit" name="phone" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                            </div>
                            
                            <!-- Role -->
                            <div>
                                <label for="role" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Role</label>
                                <select id="role" name="role" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <!-- Password (optional) -->
                            <div class="tw-col-span-2">
                                <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Password (leave blank to keep current)</label>
                                <input type="password" id="password" name="password" class="tw-bg-gray-700 tw-border tw-border-gray-600 tw-text-white tw-text-sm tw-rounded-lg tw-block tw-w-full tw-p-2.5 focus:tw-border-blue-500 focus:tw-ring-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions Section -->
                    <div class="tw-flex tw-justify-between tw-mt-8 tw-pt-4 tw-border-t tw-border-gray-700">
                        <button type="button" data-modal-toggle="editUser-modal" class="tw-text-gray-300 tw-bg-gray-700 hover:tw-bg-gray-600 tw-font-medium tw-rounded-lg tw-text-sm tw-px-5 tw-py-2.5 tw-text-center">
                            Cancel
                        </button>
                        <button type="submit" id="saveUserBtn" class="tw-text-white tw-bg-blue-600 hover:tw-bg-blue-700 tw-font-medium tw-rounded-lg tw-text-sm tw-px-5 tw-py-2.5 tw-text-center tw-flex tw-items-center">
                            <i class="fas fa-save tw-mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global variable to store the editing user's ID
    let editingUserID = null;
    
    // Function to open edit user modal with data
    window.openEditUserModal = function(userId) {
        // Store the user ID we're editing
        editingUserID = userId;
        
        // Show loading state
        const editUserModal = document.getElementById('editUser-modal');
        if (!editUserModal) {
            console.error('Edit user modal not found in DOM');
            return;
        }
        
        // Show modal
        editUserModal.classList.remove('tw-hidden');
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fetch user data
        fetch("{{ route('admin.users.show', ['id' => ':userId']) }}".replace(':userId', userId), {           
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                console.error('Server responded with status:', response.status);
                return response.json().then(err => {
                    throw new Error(err.message || 'Failed to load user data');
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to load user data');
            }
            
            // Fill form with user data
            populateEditForm(data.user);
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to load user data',
                icon: 'error',
                confirmButtonColor: '#24CFF4',
                background: '#374151',
                color: '#fff'
            });

            editUserModal.classList.add('tw-hidden');
        });
    };
    
    // Function to populate edit form with user data
    function populateEditForm(user) {
        // Set form field values
        document.getElementById('editUserID').value = user.userID;
        document.getElementById('firstName').value = user.firstName;
        document.getElementById('lastName').value = user.lastName;
        document.getElementById('email-edit').value = user.email;
        document.getElementById('username-edit').value = user.username;
        document.getElementById('phone-edit').value = user.phone;
        document.getElementById('role').value = user.role;
        document.getElementById('password').value = ''; // Clear password field
        
        // Set profile image
        const userImage = document.getElementById('editUserImage');
        if (user.profileImage) {
            userImage.innerHTML = `<img src="${user.profileImage}" alt="${user.firstName}" class="tw-h-full tw-w-full tw-object-cover">`;
        } else {
            userImage.innerHTML = `
                <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-bg-gray-700">
                    <i class="fas fa-user tw-text-4xl tw-text-gray-500"></i>
                </div>
            `;
        }
    }
    
    // Image upload preview handler
    const userImageUpload = document.getElementById('userImageUpload');
    const editUserImage = document.getElementById('editUserImage');
    
    if (userImageUpload && editUserImage) {
        userImageUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editUserImage.innerHTML = `<img src="${e.target.result}" alt="Preview" class="tw-h-full tw-w-full tw-object-cover">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle form submission
    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Show loading state
            const saveButton = document.getElementById('saveUserBtn');
            const originalButtonHTML = saveButton.innerHTML;
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin tw-mr-2"></i> Saving...';
            
            // Create FormData object (handles file uploads)
            const formData = new FormData(this);
            // Make sure method spoofing is set
            formData.append('_method', 'PUT');

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Send update request
            fetch("{{ url('/admin/users') }}/" + editingUserID, {
                method: 'POST', // POST with _method=PUT for Laravel method spoofing
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    // Don't set Content-Type with FormData
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Server responded with status:', response.status);
                    // Try to parse error response if possible
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Server returned status ' + response.status);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully',
                        icon: 'success',
                        confirmButtonColor: '#24CFF4',
                        background: '#374151',
                        color: '#fff'
                    }).then(() => {
                        // Hide modal and refresh data
                        document.getElementById('editUser-modal').classList.add('tw-hidden');
                        // Reload the table or page
                        if (window.UsersPage && window.UsersPage.usersTable) {
                            window.UsersPage.usersTable.ajax.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to update user');
                }
            })
            .catch(error => {
                console.error('Error updating user:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to update user',
                    icon: 'error',
                    confirmButtonColor: '#24CFF4',
                    background: '#374151',
                    color: '#fff'
                });
            })
            .finally(() => {
                // Restore button state
                saveButton.disabled = false;
                saveButton.innerHTML = originalButtonHTML;
            });
        });
    }
    
    // Modal close handler
    const editModalToggle = document.querySelector('[data-modal-toggle="editUser-modal"]');
    if (editModalToggle) {
        editModalToggle.addEventListener('click', function() {
            document.getElementById('editUser-modal').classList.add('tw-hidden');
        });
    }
    
    // Connect edit button from view modal
    const viewEditUserBtn = document.getElementById('editUserBtn');
    if (viewEditUserBtn) {
        viewEditUserBtn.addEventListener('click', function() {
            // Get the current user ID from the view modal
            if (window.currentUserData) {
                // Hide view modal first
                document.getElementById('viewUser-modal').classList.add('tw-hidden');
                // Use userID if exists; otherwise fallback to id
                const id = window.currentUserData.userID || window.currentUserData.id;
                console.log('Edit user with ID:', id);
                openEditUserModal(id);
            }
        });
    }
});
</script>