<div class="row">
	{if isset($facebook_link)}
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<a href="{$facebook_link}" class="btn btn-block btn-social btn-facebook">
				<span class="fa fa-facebook"></span> 
				{l s="Use facebook for sign in or login" mod="asksociallogin"}
			</a>
		</div>
	{/if}
	{if isset($google_link)}
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<a href="{$google_link}" class="btn btn-block btn-social btn-google">
				<span class="fa fa-google"></span>
				{l s="Use facebook for sign in or login" mod="asksociallogin"}
			</a>
		</div>
	{/if}
	<hr><br>
</div>