<section>
    <header>
        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-lg text-gray-600 dark:text-gray-400"
            >{{ __('Saved.') }}</p>
        @endif

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Grid Layout for Form Fields -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start inline-flex">
            <!-- Profile Picture Upload -->
            <div class="flex flex-col items-center gap-4">
                <x-input-label for="profile_picture" :value="__('Profile Picture')" />

                <!-- Drag-and-Drop Upload Area -->
                <div 
                    id="drop-area" 
                    class="w-40 h-40 lg:w-48 lg:h-48 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden cursor-pointer border-2 border-dashed border-gray-400 hover:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                    onclick="document.getElementById('profile_picture').click()"
                    ondragover="event.preventDefault()" 
                    ondrop="handleDrop(event)"
                    tabindex="0" 
                    role="button"
                    aria-label="{{ __('Upload Profile Picture') }}">

                    <!-- Profile Picture Preview -->
                    <img 
                        id="profile-picture-preview" 
                        src="{{ $user->getProfilePictureUrl() ?: asset('images/default-avatar.png') }}" 
                        alt="{{ __('Profile Picture') }}" 
                        class="object-cover w-full h-full rounded-full"
                    />
                </div>

                <!-- Hidden File Input -->
                <input 
                    id="profile_picture" 
                    name="profile_picture" 
                    type="file" 
                    accept="image/*" 
                    class="hidden" 
                    onchange="openCropper(event)" 
                />

                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                
                <!-- Drag-and-Drop Instructions -->
                <p class="text-xs text-gray-600 dark:text-gray-400 text-center">
                    {{ __('Drag and drop a file or click to select an image.') }}
                </p>
            </div>

            <!-- Cropper Modal -->
            <div id="cropper-modal" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-auto relative">
                    <h2 class="text-lg font-semibold mb-4">{{ __('Crop Image') }}</h2>

                    <!-- Cropper Image -->
                    <div class="w-full h-64">
                        <img id="crop-image" src="" alt="Crop Image" class="max-w-full max-h-full object-contain" />
                    </div>

                    <!-- Cropper Actions -->
                    <div class="flex justify-end gap-4 mt-4">
                        <button 
                            id="cancel-crop" 
                            class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500"
                            type="button">
                            {{ __('Cancel') }}
                        </button>
                        <button 
                            id="save-crop" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                            type="button">
                            {{ __('Crop') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div id="preview-section" class="hidden text-center">
                <h3 class="text-sm font-medium mb-4">{{ __('Cropped Preview') }}</h3>
                <img 
                    id="cropped-preview" 
                    src="" 
                    alt="{{ __('Cropped Preview') }}" 
                    class="w-48 h-48 rounded-full mx-auto object-cover border border-gray-300"
                />
            </div>
        
            <div class="space-y-6 mt-4">
                <!-- Name Field -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email Field -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Address Field -->
                <div>
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address ?? '')" required autocomplete="address" />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>

        <!-- Modal for Cropping -->
        <div id="cropper-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-4 max-w-lg w-70vh">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Crop your image') }}</h3>
                <div class="crop-container">
                    <img id="crop-image" src="" alt="To Crop">
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button 
                        id="cancel-crop" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md">{{ __('Cancel') }}</button>
                    <button 
                        id="save-crop" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md">{{ __('Crop') }}</button>
                </div>
            </div>
        </div>
    </form>

    <style>
        #cropper-modal {
            z-index: 50;
        }
        .crop-container img {
            max-width: 100%;
            max-height: 400px;
        }
    </style>

    <script>
        let cropper;
        let previousPictureURL;
        const input = document.getElementById('profile_picture');
        const modal = document.getElementById('cropper-modal');
        const cropImage = document.getElementById('crop-image');
        const cancelCrop = document.getElementById('cancel-crop');
        const saveCrop = document.getElementById('save-crop');
        const previewSection = document.getElementById('preview-section');
        const croppedPreview = document.getElementById('cropped-preview');
        const preview = document.getElementById('profile-picture-preview');

        // Save the current profile picture URL on page load
        window.addEventListener('load', () => {
            previousPictureURL = preview.src;
        });

        // When a file is selected
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    cropImage.src = e.target.result;

                    // Show modal and Crop button
                    modal.classList.remove('hidden');
                    saveCrop.classList.remove('hidden');

                    // Initialize or reinitialize Cropper.js
                    if (cropper) {
                        cropper.destroy(); // Destroy any existing Cropper instance
                    }
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1, // Square aspect ratio
                        viewMode: 2,    // Restrict crop box to the canvas
                        autoCropArea: 0.5, // Center crop box
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Cancel cropping
        cancelCrop.addEventListener('click', () => {
            if (cropper) cropper.destroy(); // Destroy Cropper.js instance
            cropper = null;

            // Restore the previous profile picture URL
            preview.src = previousPictureURL;

            // Reset modal and buttons
            modal.classList.add('hidden'); // Hide modal
            saveCrop.classList.add('hidden'); // Hide Crop button
            previewSection.classList.add('hidden'); // Hide preview section
        });

        // Crop image
        saveCrop.addEventListener('click', () => {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 300, // Final cropped image width
                height: 300, // Final cropped image height
            });

            if (canvas) {
                // Update the preview with the cropped image
                const croppedDataURL = canvas.toDataURL();
                croppedPreview.src = croppedDataURL;

                // Update the previous picture URL
                previousPictureURL = croppedDataURL;

                // Replace the file input with the cropped image
                canvas.toBlob((blob) => {
                    const fileInput = new File([blob], input.files[0].name, { type: blob.type });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(fileInput);
                    input.files = dataTransfer.files;
                });

                // Close the modal
                cropper.destroy();
                cropper = null;
                modal.classList.add('hidden'); // Hide the modal
                previewSection.classList.remove('hidden'); // Show the preview section
            }
        });

        // Preview the uploaded image before opening the cropper
        function openCropper(event) {
            const input = event.target;
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Update preview
                };
                reader.readAsDataURL(file);
            }
        }

        function handleDrop(event) {
            event.preventDefault();
            const input = document.getElementById('profile_picture');
            const files = event.dataTransfer.files;
            if (files.length) {
                input.files = files;
                const changeEvent = new Event('change');
                input.dispatchEvent(changeEvent);
            }
        }
    </script>
</section>
