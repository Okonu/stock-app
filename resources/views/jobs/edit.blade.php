<!-- edit.blade.php -->

@extends('layouts.app')
@section('content')

<div class="container" style="margin-top: 100px;">
    <div  class="text-center"><h1>Edit Job</h1></div>

    <form action="{{ route('jobs.update', $job->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Job Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $job->title }}" required>
        </div>

        <div class="form-group">
            <label for="description">Job Description:</label>
            <textarea name="description" id="editor" class="form-control" required>{{ $job->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="skills">Skills and Qualifications:</label>
            <select name="skill_id[]" class="form-control form-control-lg default-select select2" multiple>
                @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}" {{ in_array($skill->id, $job->skills->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $skill->skill }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="applicants">No of Applicants Desired:</label>
            <input type="number" name="applicants" id="applicants" class="form-control" value="{{ $job->applicants }}" required>
        </div>

        <div class="form-group">
            <label for="vacancies">Vacancies:</label>
            <input type="number" name="vacancies" id="vacancies" class="form-control" value="{{ $job->vacancies }}" required>
        </div>

        <div class="form-group">
            <label for="location">Location:</label>
            <select name="location_id" class="form-control form-control-lg default-select">
                <option value="" selected disabled>Job Location</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ $job->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="type">Job Type:</label>
            <select name="type" class="form-control form-control-lg default-select">
                <option value="Contract" {{ $job->type == 'Contract' ? 'selected' : '' }}>Contract</option>
                <option value="Casual" {{ $job->type == 'Casual' ? 'selected' : '' }}>Casual</option>
                <option value="Full time" {{ $job->type == 'Full time' ? 'selected' : '' }}>Full time</option>
                <option value="Part time" {{ $job->type == 'Part time' ? 'selected' : '' }}>Part time</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
</div>

<!-- Include Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- Include CKEditor files -->
<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
<!-- Initialize Select2 and CKEditor -->
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select skills',
            allowClear: true,
            tags: true,
            tokenSeparators: [','],
            minimumInputLength: 1,
            dropdownParent: $('body'), // Append the dropdown to the body
            maximumSelectionLength: 10, // Adjust the maximum number of selections allowed
            templateResult: function (data) {
                // Exclude the "Job Skills" option from the selection
                if (data.id === "") {
                    return null;
                }
                return data.text;
            },
            templateSelection: function (data) {
                // Exclude the "Job Skills" option from the selection
                if (data.id === "") {
                    return null;
                }
                return data.text;
            }
        });

        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    });
</script>

@endsection