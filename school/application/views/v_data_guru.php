				<table border="0" cellpadding="0" cellspacing="0"  id="id-form" width="100%">
					<tr>
						<td>Cari berdasarkan nomor induk</td>
						<td>Cari berdasarkan nama</td>
					</tr>
					
					<tr>
						<?php echo form_open('c_master_data/show_data_guru');?>
						<td><input name="search_field1" type="text" class="inp-form" /> <?php echo form_submit('submit', 'Cari');?> </td>
						<?php echo form_close();?>
						
						<?php echo form_open('c_master_data/show_data_guru');?>
						<td><input name="search_field2" type="text" class="inp-form" /> <?php echo form_submit('submit', 'Cari');?></td>
						<?php echo form_close();?>
					</tr>
				</table>
				
				<?=$result?>
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left"><a href="">No</a></th>
					<th class="table-header-repeat line-left"><a href="">NIP</a></th>
					<th class="table-header-repeat line-left"><a href="">Nama</a></th>
					<th class="table-header-repeat line-left"><a href="">Action</a></th>
				</tr>
				
				<?php
					$i = 0; 
					foreach ($guru as $item){?>
				
				<tr class="alternate-row">
					<td><?=++$i ?></td>
					<td><?=$item->nomor_induk_pengajar ?></td>
					<td><?=$item->nama_pengajar ?></td>
					<td>
					<?="<a href='".base_url()."c_master_data/form_pengajar/2/$item->nomor_induk_pengajar' title='Edit' class='icon-1 info-tooltip'>";?></a>
					<?="<a href='".base_url()."c_master_data/delete_pengajar/$item->nomor_induk_pengajar' title='Delete' class='icon-2 info-tooltip'>";?></a>
					</td>
				</tr>
				
				<?php } ?>
				</table>
				<!--  end product-table................................... --> 
				</form>
				<!--  start actions-box ............................................... -->
				<div id="actions-box">
					<a href="" class="action-slider"></a>
					<div id="actions-box-slider">
						<a href="/school/c_master_data/form_pengajar/1" class="action-edit">Add</a>
					</div>
					<div class="clear"></div>
				</div>
				<!-- end actions-box........... -->