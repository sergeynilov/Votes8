<h1 class="card-title">
	<table>
		<tr>
			@if(empty($is_developer_comp))
			<td style="width: 90px;">
				<img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
			</td>
			@endif

			<td style="width: 290px;">
				{{$site_name}}
			</td>
		</tr>
	</table>
</h1>

