@if ($alreadySubmitted)
    <button class="btn btn-primary mt-5" disabled>Already Submitted</button>
@else
    <button class="btn btn-primary mt-5" onclick="document.getElementById('password_modal').showModal();">
        Submit Attendance
    </button>
    <dialog id="password_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Enter Password</h3>
            <form method="POST" action="{{ route('attendance.submit', ['id' => $attendance->id]) }}">
                @csrf
                <div class="form-control">
                    <label for="password" class="label">Password</label>
                    <input type="password" id="password" name="password" class="input input-bordered" required>
                </div>
                <div class="form-control mt-4">
                    <label for="status" class="label">Status</label>
                    <select id="status" name="status" class="select select-bordered" required>
                        <option value="present">Hadir</option>
                        <option value="absent">Tidak Hadir</option>
                        <option value="late">Terlambat</option>
                    </select>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('password_modal').close();">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endif
