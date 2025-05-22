{{-- @extends('layouts.app') --}}
@include('includes.bootstrap')    


{{-- @section('content') --}}
{{-- <div class="container" style="margin:  0 0 0 30% "> --}}
<h2 style="margin:  10% 0 2% 32% ">📊 statistics of docs</h2>
<table border="1" style="margin:  0 0 0 30% " cellpadding="10" cellspacing="0" style="margin-top:20px;">
    <tbody>
            
                <tr>
                    <td>number of docs</td> 
                    <td>{{ $totalDocuments }}</td>
                </tr>
                <tr>
                    <td>total files size :</td> 
                    <td>{{ $totalSizeMB }} MB</td>
                </tr>
                <tr>
                    <td>avg of serch time</td> 
                    <td>{{ $averageSearchTime }} sec</td>
                </tr>
                <tr>
                    <td>avg of Classify Time</td> 
                    <td>{{ $averageClassifyTime }} sec</td>
                </tr>             
                <tr>
                    <td>avg of Sort Time</td> 
                    <td>{{ $averageSortTime }} sec</td>
                </tr>             
    </tbody>
 </table>   
         <a href="{{ route('documents.list') }}" style="margin:  0 0 0 30% " class="btn btn-primary mt-3">Back to the docs</a>

   




   

{{-- @endsection --}}
