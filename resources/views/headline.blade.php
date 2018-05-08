@extends('layout')
@section('content')
<div class="row">
	<div class="col-md-6">
		<strong> Showing {{($headlines->currentpage()-1)*$headlines->perpage()+1}} to @if($headlines->total() > ($headlines->currentpage()*$headlines->perpage())) {{$headlines->currentpage()*$headlines->perpage()}}
			@else
			{{ $headlines->total() }}
			@endif
	    of  {{$headlines->total()}} entries </strong>
	</div>
	<div class="col-md-6 float-right text-right">
		<a href="{{ route('storyDownload') }}?type=csv">CSV</a> | 
		<a href="{{ route('storyDownload') }}?type=json">Json</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">Heading</th>
		      <th scope="col">Content</th>
		      <th scope="col">Images</th>
		    </tr>
		  </thead>
		  <tbody>
		    @foreach($headlines as $headline)
			<!-- Modal -->
			<div class="modal fade" id="{{ $headline->story_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">{{ $headline->headline }}</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        {{ $headline->story_content }}
			      </div>
			    </div>
			  </div>
			</div>
		    <tr>
		    	<td>{{ $headline->headline }}</td>
		    	<td>{{ str_limit(strip_tags($headline->story_content), 350) }}
		            @if (strlen(strip_tags($headline->story_content)) > 350)
		            <a href="#" data-toggle="modal" data-target="#{{ $headline->story_id }}">Read More</a>
		            @endif
		        </td>
		    	<td>
		    		@foreach($headline->images as $image)
		    		<img src="{{ Storage::url($image->image_path) }}" class="img-fluid">
		    		@endforeach
		    	</td>
		    </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		{{ $headlines->links() }}
	</div>
</div>
@endsection