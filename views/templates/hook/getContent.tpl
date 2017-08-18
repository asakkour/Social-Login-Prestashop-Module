{if (isset($confirmation))}
	<div class="bootstrap">
		<div class="module_confirmation conf confirm alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			Paramètres mis à jour
		</div>
	</div>
{/if}
<form method="post" accept="" class="defaultForm form-horizontal">
	<div class="panel">
		<div class="panel-heading">
			<i class="icon-cogs"></i> {l s='Social Login  and Sign Using Facebook and google account' mod='asksociallogin'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="the default group ID for new customers" mod="asksociallogin"}</label>
				</div>
				<div class="col-lg-7">
					<input type="text" id="facebook_app_id" name="ask_social_default_group" {if isset($ask_social_default_group) } value="{$ask_social_default_group}"  {/if}>
				</div>
			</div>
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Login and Sign in by facebook" mod="askmymodcomments"}</label>
				</div>
				<div class="col-lg-7">
					<img src="../img/admin/enabled.gif">
					<input type="radio" id="enable_facebook_1" name="ask_facebook_login" value="1" {if $ask_facebook_login eq '1'} checked  {/if}>
					<label class="t" for="enable_facebook_1">{l s='Yes' mod='askmymodcomments'} </label>
					<img src="../img/admin/disabled.gif">
					<input type="radio" id="enable_facebook_0" name="ask_facebook_login" value="0" {if $ask_facebook_login eq '0'} checked  {/if}>
					<label class="t" for="enable_facebook_0">{l s='No' mod='askmymodcomments'} </label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Facebook App ID" mod="asksociallogin"}</label>
				</div>
				<div class="col-lg-7">
					<input type="text" id="facebook_app_id" name="ask_facebook_app_id" {if isset($ask_facebook_app_id) } value="{$ask_facebook_app_id}"  {/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Facebook App Secret" mod="asksociallogin"}</label>
				</div>
				<div class="col-lg-7">
					<input type="text" id="facebook_app_id" name="ask_facebook_app_secret" {if isset($ask_facebook_app_secret) } value="{$ask_facebook_app_secret}"  {/if}>
				</div>
			</div>
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Login and Sign in using google account" mod="askmymodcomments"}</label>
				</div>
				<div class="col-lg-7">
					<img src="../img/admin/enabled.gif">
					<input type="radio" id="enable_google_1" name="ask_google_login" value="1" {if $ask_google_login eq '1'} checked  {/if}>
					<label class="t" for="enable_google_1">{l s='Yes' mod='askmymodcomments'} </label>
					<img src="../img/admin/disabled.gif">
					<input type="radio" id="enable_google_0" name="ask_google_login" value="0" {if $ask_google_login eq '0'} checked  {/if}>
					<label class="t" for="enable_google_0">{l s='No' mod='askmymodcomments'} </label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Google Client ID" mod="asksociallogin"}</label>
				</div>
				<div class="col-lg-7">
					<input type="text" name="ask_google_app_id" {if isset($ask_google_app_id) } value="{$ask_google_app_id}"  {/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-lg-offset-2">
					<label>{l s="Google Client Secret" mod="asksociallogin"}</label>
				</div>
				<div class="col-lg-7">
					<input type="text" name="ask_google_app_secret" {if isset($ask_google_app_secret) } value="{$ask_google_app_secret}"  {/if}>
				</div>
			</div>
			<div class="panel-footer">
				<button type="submit" value="1" id="module_form_submit_btn" name="submit_my_new_mod_social_setting" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s="save" mod="asksociallogin"}
				</button>
			</div>
		</div>
	</div>
</form>