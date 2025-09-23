<x-app-layout>
    <div class="content-header"><h1>Book a New Appointment</h1></div>

    <!-- ============== ADD THIS NEW INSTRUCTION CARD ============== -->
    <div class="card appointment-instructions">
        <div>
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256">
                <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm16-40a8,8,0,0,1-8,8,16,16,0,0,1-16-16V128a8,8,0,0,1,0-16,16,16,0,0,1,16,16v40A8,8,0,0,1,144,176ZM112,88a12,12,0,1,1,12,12A12,12,0,0,1,112,88Z"></path>
            </svg>
        </div>
        <div>
            <h4>Important: Please Read Before Booking</h4>
            <p>
                Please only book an appointment here if you have received a message from your doctor instructing you to do so. Your doctor will provide you with the specific date you should select.
                <br><br>
                If the date provided is not suitable, please do not book a different time. Instead, you must go to the hospital directly to make an appointment in person or call the hospital Taiping official number.
            </p>
        </div>
    </div>
    <!-- ========================================================== -->

    <div class="card">
        <form method="POST" action="{{ route('patient.appointments.store') }}">
            @csrf
            {{-- The rest of your form code stays the same --}}
            <div class="form-group">
                <label for="doctor_id" class="form-label">Select Doctor</label>
                <select id="doctor_id" name="doctor_id" class="form-select" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date" class="form-label">Appointment Date and Time</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reason" class="form-label">Reason for Appointment</label>
                <textarea id="reason" name="reason" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
    </div>
</x-app-layout>