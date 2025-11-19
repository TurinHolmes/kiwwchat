{{-- ---------------------- Image modal box ---------------------- --}}
<div id="imageModalBox" class="imageModal">
    <span class="imageModal-close">&times;</span>
    <img class="imageModal-content" id="imageModalBoxSrc">
</div>

{{-- ---------------------- Delete Modal ---------------------- --}}
<div class="app-modal" data-name="delete">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="delete" data-modal='0'>
            <div class="app-modal-header">Are you sure you want to delete this?</div>
            <div class="app-modal-body">You can not undo this action</div>
            <div class="app-modal-footer">
                <a href="javascript:void(0)" class="app-btn cancel">Cancel</a>
                <a href="javascript:void(0)" class="app-btn a-btn-danger delete">Delete</a>
            </div>
        </div>
    </div>
</div>

{{-- ---------------------- Alert Modal ---------------------- --}}
<div class="app-modal" data-name="alert">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="alert" data-modal='0'>
            <div class="app-modal-header"></div>
            <div class="app-modal-body"></div>
            <div class="app-modal-footer">
                <a href="javascript:void(0)" class="app-btn cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>

{{-- ---------------------- Settings Modal (MODIFIED TO VIEW PROFILE) ---------------------- --}}
<div class="app-modal" data-name="settings">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="settings" data-modal='0'>
            {{-- Header Modal --}}
            <div class="app-modal-header" style="text-align: center; border-bottom: none;">
                User Profile
            </div>

            {{-- Mengambil Data Pengguna --}}
            @php
                $uid = Auth::id();
                $userModel = \App\Models\User::find($uid);
                $currentUser = Chatify::getUserWithAvatar($userModel);
                $avatarUrl = $currentUser->avatar ?? Chatify::getUserAvatarUrl();
            @endphp

            {{-- Form hanya digunakan untuk upload avatar saja --}}
            <form id="update-settings" action="{{ route('avatar.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="app-modal-body">

                    {{-- PERBAIKAN: Gunakan display: flex dan justify-content: center pada div terluar --}}
                    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                        <div class="avatar-container" style="position: relative;">
                            <div class="avatar av-l upload-avatar-preview"
                                style="background-image: url('{{ $avatarUrl }}');">
                            </div>

                            {{-- IKON EDIT AVATAR: Style lebih kuat --}}
                            <label class="app-btn a-btn-primary upload-avatar-label update" title="Ubah Avatar"
                                style="
                  position: absolute; 
                  bottom: -5px; /* Disesuaikan */
                  right: -5px; /* Disesuaikan */
                  cursor: pointer; 
                  color: white; 
                  background-color: var(--primary-color, #3490dc); 
                  border-radius: 50%; 
                  padding: 6px; 
                  line-height: 1; 
                  border: 2px solid white; 
                  height: 32px; 
                  width: 32px; 
                  display: flex; 
                  align-items: center; 
                  justify-content: center;
                  box-shadow: 0 2px 5px rgba(0,0,0,0.2); 
                  z-index: 10; 
                ">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px; margin: 0;"></i>

                                <input class="upload-avatar chatify-d-none" type="file" name="avatar"
                                    accept="image/*" />
                            </label>
                        </div>
                    </div>

                    {{-- Menampilkan Data Pengguna (HANYA VIEW) --}}
                    {{-- PERBAIKAN: Tambahkan text-align: center; agar konten di dalamnya rata tengah --}}
                    <div class="profile-details" style="margin-top: 30px; text-align: center;">
                        <div class="detail-row" style="margin-bottom: 10px; padding: 5px 0;">
                            <label style="font-weight: bold; display: block; color: #777;">Name:</label>
                            <span style="font-size: 16px;">{{ $currentUser->name }}</span>
                        </div>
                        <div class="detail-row" style="margin-bottom: 10px; padding: 5px 0;">
                            <label style="font-weight: bold; display: block; color: #777;">Email:</label>
                            <span style="font-size: 16px;">{{ $currentUser->email }}</span>
                        </div>
                    </div>
                </div>

                {{-- FOOTER: HANYA TOMBOL CANCEL DAN EDIT PROFILE --}}
                <div class="app-modal-footer"
                    style="padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between;">
                    <a href="javascript:void(0)" class="app-btn cancel" style="width: 48%;">Close</a>
                    {{-- Tombol untuk navigasi ke Edit Profile Breeze --}}
                    <a href="{{ route('profile.edit') }}" class="app-btn a-btn-primary update" style="width: 48%;">
                        Edit
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
