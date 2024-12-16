@extends('layouts.main')

@section('main-container')
    <div class="container mt-5">
        <h2>
            {{!empty($user) ? 'Edit' : 'Add'}} User</h2>
        <form action="{{ !empty($user) ? route('users.update', ['id' => $user->id]) : route('users.create') }}" method="POST"
            id="user-form">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value={{ !empty($user) ? $user->first_name : old('last_name') }}>
                            @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value={{ !empty($user) ? $user->last_name : old('last_name') }}>
                        @if ($errors->has('last_name'))
                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                        @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value={{ !empty($user) ? $user->email : old('email') }}>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @enderror
            </div>
        </div>
        <div class="col-md-6">
            @if (empty($user))
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        value={{ !empty($user) ? $user->password : old('password') }}>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @enderror
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone"
                value={{ !empty($user) ? $user->phone : old('phone')  }}>
            @if ($errors->has('phone'))
                <span class="text-danger">{{ $errors->first('phone') }}</span>
            @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Gender</label>

        <div id="gender">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="male" name="gender"
                    value="Male" {{ !empty($user) && $user->gender == 'Male' ? 'checked' : ''}}>
                <label class="form-check-label" for="male">
                    Male
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="female" name="gender"
                    value="Female" {{ !empty($user) && $user->gender == 'Female' ? 'checked' : '' }}>
                <label class="form-check-label" for="female">
                    Female
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="other" name="gender"
                    value="Other" {{ !empty($user) && $user->gender == 'Other' ? 'checked' : '' }}>
                <label class="form-check-label" for="other">
                    Other
                </label>
            </div>
        </div>
        @if ($errors->has('gender'))
            <span class="text-danger">{{ $errors->first('gender') }}</span>
        @enderror
</div>
</div>

</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
    <label for="date_of_birth">Date of Birth</label>
    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
        value={{ !empty($user) ? $user->date_of_birth : old('date_of_birth') }}>
    @if ($errors->has('date_of_birth'))
        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
    @enderror
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label for="status">Status</label>
<select class="form-control" id="status" name="status">
    <option value="1" {{ !empty($user) && $user->status == 1 ? 'selected' : old('last_name') }}>Active
    </option>
    <option value="0"{{ !empty($user) && $user->status == 0 ? 'selected' : old('last_name') }}>Inactive
    </option>
</select>
@if ($errors->has('status'))
    <span class="text-danger">{{ $errors->first('status') }}</span>
@enderror
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label for="address_1">Current Address</label>
<textarea class="form-control" id="address_1" name="address_1">{{ !empty($user) ? $user->address_1 : old('last_name') }}</textarea>
@if ($errors->has('address_1'))
<span class="text-danger">{{ $errors->first('address_1') }}</span>
@enderror
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label for="address_2">Permanent Address</label>
<textarea class="form-control" id="address_2" name="address_2">{{ !empty($user) ? $user->address_2 : old('last_name') }}</textarea>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label for="country">Country</label>
<select class="form-control" id="country" name="country">
<option value="">Select a country</option>
@foreach ($countries as $country)
<option value="{{ $country['id'] }}"
    {{ old('country', !empty($user) ? $user->country_id : '') == $country['id'] ? 'selected' : '' }}>
    {{ $country['name'] }}
</option>
@endforeach
</select>
@if ($errors->has('country'))
<span class="text-danger">{{ $errors->first('country') }}</span>
@enderror
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<button type="submit" class="btn btn-primary">{{!empty($user) ? 'Update' : 'Save'}} User</button>
<a href="{{ route('users.list') }}" class="btn btn-secondary">Cancel</a>
</div>
</div>
</div>
</form>

</div>

<script>
    $(document).ready(function() {
        $.validator.addMethod("notFutureDate", function(value, element) {
            var selectedDate = new Date(value);
            var today = new Date();
            return this.optional(element) || selectedDate <= today;
        }, "Date cannot be in the future.");

        $.validator.addMethod("phonePattern", function(value, element) {
            return this.optional(element) || /^[\d\+\-\(\)\s]*$/.test(value);
        }, "The phone field format is invalid.");

        $("#user-form").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                phone: {
                    required: true,
                    maxlength: 20,
                    phonePattern: true
                },
                gender: {
                    required: true
                },
                date_of_birth: {
                    required: true,
                    date: true,
                    notFutureDate: true
                },
                status: {
                    required: true
                },
                address_1: {
                    required: true,
                    maxlength: 255
                },
                address_2: {
                    maxlength: 255
                },
                country: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    required: "Please enter your first name.",
                    minlength: "Your first name must consist of at least 2 characters."
                },
                last_name: {
                    required: "Please enter your last name.",
                    minlength: "Your last name must consist of at least 2 characters."
                },
                email: {
                    required: "Please enter a valid email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please provide a password.",
                    minlength: "Your password must be at least 6 characters long."
                },
                phone: {
                    required: "Please provide your phone number.",
                    maxlength: "Your phone number cannot exceed 20 digits.",
                    phonePattern: "The phone field format is invalid."
                },
                gender: {
                    required: "Please select your gender."
                },
                date_of_birth: {
                    required: "Please provide your date of birth.",
                    date: "Please enter a valid date.",
                    notFutureDate: "Date cannot be in the future."
                },
                status: {
                    required: "Please select the status."
                },
                address_1: {
                    required: "Please enter your address.",
                    maxlength: "Address cannot exceed 255 characters."
                },
                address_2: {
                    maxlength: "Address cannot exceed 255 characters."
                },
                country: {
                    required: "Please enter your country."
                }
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            }
        });
    });
</script>
@endsection
