@extends('layouts.master')

@section('content')
<div class="p-6">
    <h2 class="text-lg font-medium">Student Voters</h2>
    <p class="text-sm text-gray-600">Select a voting exclusive to print student voters report.</p>

    <div class="mt-4 bg-white p-4 rounded shadow">
        <div class="mb-4">
            <label for="voting-select" class="block text-sm font-medium text-gray-700">Voting Exclusive</label>
            <div class="mt-1 flex gap-2">
                <select id="voting-select" class="border rounded p-2" style="min-width:300px">
                    @foreach($votings as $v)
                        <option value="{{ $v->id }}">{{ $v->id }} - {{ $v->department_id ? ($v->department->department_name ?? 'Dept') : 'All Departments' }} ({{ $v->start_datetime }} - {{ $v->end_datetime }})</option>
                    @endforeach
                </select>
                <a id="print-link" href="#" target="_blank" class="text-blue-600 self-center">Print PDF</a>
            </div>
        </div>

        <div class="mb-4">
            <input id="search-input" type="text" placeholder="Search by student id or name..." class="w-full border rounded p-2" />
        </div>

        <table class="w-full" id="voters-table">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Record ID</th>
                    <th class="text-left">Student ID</th>
                    <th class="text-left">Student Name</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Voted At</th>
                </tr>
            </thead>
            <tbody>
                {{-- Table will be populated via AJAX --}}
                <tr><td colspan="8" class="text-center text-gray-500">Select a voting exclusive above to load voters.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        // Ensure axios is available; if not, load from CDN then continue
        function ensureAxios(cb){
            if(window.axios) return cb();
            var s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/axios@1.7.4/dist/axios.min.js';
            s.onload = function(){ cb(); };
            s.onerror = function(){ console.error('Failed to load axios from CDN'); cb(); };
            document.head.appendChild(s);
        }

        ensureAxios(function(){
            const votingSelect = document.getElementById('voting-select');
        const printLink = document.getElementById('print-link');
        const searchInput = document.getElementById('search-input');
        const table = document.getElementById('voters-table');
        const tbody = table.querySelector('tbody');

        function setPrintHref(id){
            printLink.href = '/pdf/student-voters/' + id;
        }

        async function fetchVoters(id, q=''){
            try{
                const resp = await axios.get('/api/student-voters/' + id, { params: { q } });
                return resp.data.voters || [];
            } catch(err){
                console.error(err);
                return [];
            }
        }

        function renderRows(voters){
            if(!voters || voters.length === 0){
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-gray-500">No voters found.</td></tr>';
                return;
            }
            const rows = voters.map((v, idx) => {
                const name = (v.student.first_name || '') + ' ' + (v.student.last_name || '');
                // Format student_id as 222301 0001 (first 6 chars + space + last 4) when possible
                let formattedStudentId = '';
                if (v.student && v.student.student_id) {
                    const sid = String(v.student.student_id).replace(/\s+/g,'');
                    if (sid.length >= 10) {
                        formattedStudentId = sid.slice(0,6) + ' ' + sid.slice(-4);
                    } else if (sid.length > 6) {
                        // If shorter than 10 but longer than 6, split after 6
                        formattedStudentId = sid.slice(0,6) + ' ' + sid.slice(6);
                    } else {
                        formattedStudentId = sid;
                    }
                } else {
                    formattedStudentId = v.students_id || '';
                }

                return `
                    <tr>
                        <td>${idx + 1}</td>
                        <td>${v.id}</td>
                        <td>${escapeHtml(formattedStudentId)}</td>
                        <td>${escapeHtml(name)}</td>
                        <td>${escapeHtml(v.status || '')}</td>
                        <td>${v.created_at || ''}</td>
                    </tr>
                `;
            }).join('');
            tbody.innerHTML = rows;
        }

        function escapeHtml(s){
            if(!s) return '';
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
        }

        let timeout;
        async function load(){
            const id = votingSelect.value;
            setPrintHref(id);
            const q = searchInput.value.trim();
            const voters = await fetchVoters(id, q);
            renderRows(voters);
        }

            votingSelect.addEventListener('change', load);
            searchInput.addEventListener('input', function(){
            clearTimeout(timeout);
            timeout = setTimeout(load, 300);
        });

        // Initial load
        if(votingSelect.value){
            setPrintHref(votingSelect.value);
            load();
        }
        });
    })();
</script>
@endpush