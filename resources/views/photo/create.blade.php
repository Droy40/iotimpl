@extends('layout.layout')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Upload File</h2>
        <form id="uploadForm">
            @csrf
            <div class="mb-3">
                <!-- File Input -->
                <label for="formFile" class="form-label">Choose a file</label>
                <input class="form-control" type="file" id="image" name="image">
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Upload Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="waitingModal" tabindex="-1" aria-labelledby="waitingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Please wait while we process your request...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var idphotos;
            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();
                $('#waitingModal').modal('show');
                // Create FormData object
                var formData = new FormData(this);

                // AJAX request
                $.ajax({
                    url: '{{route('api.photo.store')}}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Show success message in modal
                        $('#waitingModal').modal('hide');
                        $('#modalMessage').text('File uploaded successfully!');
                        $('#resultModal').modal('show');
                        idphotos = response.result.idphotos
                    },
                    error: function (xhr, status, error) {
                        // Show error message in modal
                        $('#waitingModal').modal('hide');
                        $('#modalMessage').text('Failed to upload file. Please try again.');
                        $('#resultModal').modal('show');
                    }
                });
            });
            $('#resultModal').on('hidden.bs.modal', function () {
                console.log('Modal closed. idphotos:', idphotos);
                if (idphotos) {
                    var redirectUrl = "{{ route('photo.index') }}" + "/" + idphotos;
                    console.log('Redirecting to:', redirectUrl); // Debugging: Log redirect URL
                    window.location.href = redirectUrl; // Redirect to the constructed URL
                } else {
                    alert('Redirect failed: idphotos is undefined.');
                }
            });
        });
    </script>

@endsection
