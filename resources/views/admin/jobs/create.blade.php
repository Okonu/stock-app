@extends('admin.layouts.app')

@section('content')
    <!-- Style -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- Include Select2 JavaScript -->

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
        });
    </script>
    <style>
        .text-black {
            color: #000;
        }

        .form-control {
            color: black;
            background-color: #fff;
            border-radius: 10px;
            border-color: #000;
        }
    </style>
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <!-- Add Order -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create Job</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('admin.jobs.create') }}" method="POST">
                                @csrf

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="text-black">Job Title:</label>
                                        <input type="text" name="title" class="form-control">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="text-black">Job Description:</label>
                                        <div class="card-body custom-ekeditor">
                                            <textarea name="description" id="editor" class="form-control"></textarea>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-black">Skills And Qualifications:</label>
                                        <select name="skill_id[]" class="form-control form-control-lg default-select select2" multiple>
                                            @foreach ($skills as $skill)
                                                <option value="{{ $skill->id }}">{{ $skill->skill }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-black">No of Applicants Desired:</label>
                                        <input type="text" name="applicants" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-black">Vacancies:</label>
                                        <input type="text" name="vacancies" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-black">Location:</label>
                                        <select name="location_id" class="form-control form-control-lg default-select">
                                            <option value="" selected disabled>Job Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-black">Job Type:</label>
                                        <select name="type" class="form-control form-control-lg default-select">
                                            <option selected disabled>Choose...</option>
                                            <option>Contract</option>
                                            <option>Casual</option>
                                            <option>Full time</option>
                                            <option>Part time</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include CKEditor files -->
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <!-- Initialize CKEditor -->
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
