<div id="revsubscribe">
	<div class="container">
		<div class="row">
			<div class="col-md-2 col-lg-3"></div>
			<div class="col-sm-12 col-md-8 col-lg-6">
				<form id="revsubscribe_form">
					<div class="col-sm-12 col-md-8 col-lg-5">
						<div class="revtext">
							<span class="susbs_head_text"><?php echo $revsubscribe_title; ?></span>
							<?php if (!empty($revsubscribe_text)) { ?>
							<br>
							<?php echo htmlspecialchars_decode($revsubscribe_text); ?>
							<?php } ?>
						</div>
						<div id="revsubscribe_success"></div>
					</div>
					<div class="col-sm-12 col-md-8 col-lg-7">
						<input type="text" class="form-control" name="revs_email" id="revs_email" placeholder="<?php echo $text_revsubscribe_email_tooltip; ?>" value="" />
						<input type="hidden" name="language_id" id="language_id" value="<?php echo $language_id; ?>" />
						<input type="hidden" name="store_id" id="store_id" value="<?php echo $store_id; ?>" />
						<a id="revsubscribe_submit" class="btn btn-primary"><?php echo $revsubscribe_subscribe_now; ?></a>
					</div>
				</form>
			</div>
			<!--
			<div class="col-sm-12 col-md-8 col-lg-6">
				<div class="revtext_soc">
					<span class="susbs_head_text">Подписаться</br>на наши соцсети</span>
				</div>

				<div class="susbs_head_soc">
					<a href="https://www.instagram.com/101kamushek/followers/" rel="nofollow" target="_blank" class="soc_ins"><i class="fa fa-instagram" data-toggle="tooltip" title="Мы в Инстаграм" data-original-title="Мы в Инстаграм"></i></a>
				</div>
			</div>
			-->
		</div>
	</div>
	<script>
		<!--
		$("#revsubscribe_form").submit(function(event) {
			$('#revsubscribe_submit').click();
			event.preventDefault();
		});
		$('#revsubscribe_submit').on('click', function() {
			var email_validate = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			if ((document.getElementById("revs_email").value.length == 0)) {
				var message = '<?php echo $revsubscribe_error1; ?>';
				get_revpopup_notification('alert-danger', '<?php echo $text_product_oshibka; ?>', message);
			} else if (!document.getElementById("revs_email").value.match(email_validate)) {
				var message = '<?php echo $revsubscribe_error2; ?>';
				get_revpopup_notification('alert-danger', '<?php echo $text_product_oshibka; ?>', message);
			} else {
				$.ajax({
					url: 'index.php?route=revolution/revsubscribe/subscribecustomer',
					type: 'post',
					data: $('#revsubscribe_form').serialize(),
					success: function(response) {
						get_revpopup_notification('alert-success', '<?php echo $text_revsubscribe_spasibo; ?>', response);
						$('#revs_email').val('');
					}
				});
			}
		});
		//
		-->
	</script>