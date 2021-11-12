<style>
	.img {
		border: 0; 
		max-width: 100px;
		min-height: auto; 
		outline: none; 
		text-decoration: none; 
		vertical-align: bottom;
	}
	a.btn_approve{
		-webkit-appearance: button;
		-moz-appearance: button;
		appearance: button;
		padding: 12px 25px;
		background: #44e2ab;
		text-decoration: none;
	}
	a.btn_approve:hover{
		text-decoration: none;
		background: #3dbf91;
	}
	a:hover{
		text-decoration: none;
	}
	a.btn_approve b {
		color: #FFFFFF;
	}
	table {
		border-collapse: collapse;
		max-width: 750px !important;
	}
	.td-header {
		color: #202020;
		font-family: Helvetica;
		font-size: 16px;
		line-height: 24px;
		text-align: left;
		word-break: break-word;
		padding: 10px 0;
	}
	.td-header div {
		text-align: right;
	}
	.td-header div span {
		font-family: tahoma, verdana, segoe, sans-serif;
		font-size: 15px;
	}
	.table-1 {
		border-collapse: collapse;
		border-top: 5px solid #44e2ab;
		min-width: 100%;
	}
	.table-column {
		border-collapse:collapse;
		word-break:break-word;
		color:#202020;
		font-family:Helvetica;
		text-align:left;
		font-size:14px;
		line-height:24px;
	}
	.table-column .table-child {
		font-size:12px;
		line-height:20px;
		margin-top: 30px;
	}
	.table-column .table-child thead tr th {
		border: 1px solid #707070;
		padding: 4px;
		text-align: center;
	}
	.table-column .table-child tbody tr td {
		border:1px solid #707070;
		padding: 4px;
		text-align: right;
	}
	.semicolon {
		text-align:center;
		width:17px;
	}
	
	.td-btn {
		padding-bottom: 9px;
		padding-left:18px;
		padding-right: 18px;
		padding-top: 15px;
	}
	
	.table-end {
		border-collapse: collapse;
		border-top: 1px solid #44e2ab;
		color: #202020;
		font-family: Helvetica;
		font-size: 15px;
		line-height:24px;
		margin-top: 30px;
		min-width: 100%;
		text-align: left;
		word-break: break-word;
	}
	.table-end .td1 {
		padding-bottom: 10px;
		padding-left: 18px;
		padding-top:15px;
	}
	.table-end .td2 {
		padding-left:18px;
	}
	.table-end .td3 {
		font-weight:bold;
		padding-left:18px;
		padding-bottom:20px;
	}
	.padcon {
		padding: 20px;
	}
	.yth {
		display: block;
		margin-top: 20px;
		padding: 0 20px;
	}
</style>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td valign="top">
				<table align="left" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td valign="top">
								<a href="" title="" target="_blank">
									<img class="CToWUd img" alt="" src="" width="100">
								</a>
							</td>
						</tr>
					</tbody>
				</table>
				<table align="right" border="0" cellpadding="0" cellspacing="0" width="300">
					<tbody>
						<tr>
							<td class="td-header" valign="bottom">
								<div>
									<span>
										<b>
											<?php
												if($approval->status == 1)
													echo "Create Approval Request Item";
												else if($approval->status == 3)
													echo "Finish Approved Request Item";
												else
													echo "Finish Rejected Request Item";
											?>
										</b>
									</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="table-1" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td>
								<span></span>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="table-column" align="left" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td valign="top">
								<span class="yth">
									<?= ($approval->status == 3) ? ucwords($approval->spkRequestItem->profile->name) .' Request Item Selesai di APPROVE' : ucwords($approval->spkRequestItem->profile->name) .' Request Item di REJECT' ?>
								</span>
								<div class="padcon">
									<table class="table-column">
										<tr>
											<td>Comment</td>
											<td class="semicolon">:</td>
											<td><strong><?= nl2br($description) ?></strong></td>
										</tr>
									</table>
								</div>
								<div class="padcon">
									<table class="table-column">
										<tbody>
											<tr>
												<td>No. Request Item</td>
												<td class="semicolon">:</td>
												<td><strong><?= $approval->no_request ?></strong></td>
											</tr>
											<tr>
												<!-- Create, Reject, Approve -->
												<td><?= ($approval->status == 1) ? 'Create By' : 'Update By' ?></td>
												<td class="semicolon">:</td>
												<td><strong><?= isset($approval->spkRequestItem->profile) ? $approval->spkRequestItem->profile->name : '' ?></strong></td>
											</tr>
											<tr>
												<td>Tanggal</td>
												<td class="semicolon">:</td>
												<td><strong><?=date('d-m-Y H:i', $approval->created_at)?></strong></td>
											</tr>
										</tbody>
									</table>
									</br>
									Silahkan klik button dibawah untuk melihat data lebih lengkap yang akan di approve. </br>
									Terima kasih
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-btn" valign="top">
								<a class="btn_approve" href="<?=$url?>">
									<b>LIHAT DATA</b>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="table-end" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td class="td1" valign="top">Best Regard,</td>
						</tr>
						<tr>
							<td class="td3" valign="top">PT. Testing</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>