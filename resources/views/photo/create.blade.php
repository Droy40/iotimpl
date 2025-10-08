@extends('layout.layout')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Upload File</h2>
        <form id="uploadForm" action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <!-- File Input -->
                <label for="formFile" class="form-label">Choose a file</label>
                <input class="form-control" type="file" id="image" name="image" required>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <?php $flashMessage = session('message'); $firstError = $errors->first(); ?>

        <?php if ($flashMessage): ?>
            <div class="alert alert-success mt-3"><?php echo e($flashMessage); ?></div>
        <?php endif; ?>

        <?php if ($firstError): ?>
            <div class="alert alert-danger mt-3"><?php echo e($firstError); ?></div>
        <?php endif; ?>
    </div>
@endsection
