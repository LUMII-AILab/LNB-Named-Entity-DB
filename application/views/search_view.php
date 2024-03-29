<?php $this->load->view('shared/top');?>

<div class="tabHeader">
	<span class="name">
		<span>meklēšanas rezultāti</span>
	</span>
</div>

<!-- start pagination -->
<form id="paginatorForm" action="/namedEntityDB/browse/" method="GET">
	<input type="hidden" name="key_word" value="<?=$strKeyWord;?>" />
	<input type="hidden" name='category' value='<?=$intCategoryID?>' />
	<input type='hidden' name='order_by' value='<?=$strOrderBy?>' />
	<input type='hidden' name='order_mode' value='<?=$strOrderMode?>' />
	<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
	<input id="pageNum" type="hidden" name="pageNum" value="<?=$intPageNum;?>" />
</form>
<div>
	<table cellspacing="0" cellpadding="0" class="paginatorMainPanel">
		<tbody>
			<tr>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorLeftPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons" <?php if ($intPageNum != 1) echo "onclick=\"document.getElementById('pageNum').value='".($intPageNum-1)."'; document.forms['paginatorForm'].submit();\"" ?>>
										<div style="margin:0 -4px 0 -3px;">&lt;</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Lapa</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<input onchange="if(this.value > 0 && this.value <= <?=(ceil($intRowsCount/$intRowCountPerPage));?>) { document.getElementById('pageNum').value=this.value;} else { document.getElementById('pageNum').value=<?=(ceil($intRowsCount/$intRowCountPerPage));?>;}; document.forms['paginatorForm'].submit();" type="text" value="<?=$intPageNum;?>" />
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">no <?=(ceil($intRowsCount/$intRowCountPerPage));?></div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons" <?php if ($intPageNum != ceil($intRowsCount/$intRowCountPerPage) && $intRowsCount != 0) echo "onclick=\"document.getElementById('pageNum').value='". ($intPageNum+1) ."'; document.forms['paginatorForm'].submit();\"" ?>>
										<div style="margin:0 -4px 0 -3px;">&gt;</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorRightPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Atrasti:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="paginatorUnitCount"><?=$intRowsCount;?> rezultāti</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Rādīt lapā:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<select onchange="document.getElementById('row_count_per_page').value=this.value; document.forms['paginatorForm'].submit();">
										<option value="10" <?php if ($intRowCountPerPage == 10) echo 'selected';?>>10</option>
										<option value="20" <?php if ($intRowCountPerPage == 20) echo 'selected';?>>20</option>
										<option value="25" <?php if ($intRowCountPerPage == 25) echo 'selected';?>>25</option>
										<option value="30" <?php if ($intRowCountPerPage == 30) echo 'selected';?>>30</option>
										<option value="35" <?php if ($intRowCountPerPage == 35) echo 'selected';?>>35</option>
										<option value="40" <?php if ($intRowCountPerPage == 40) echo 'selected';?>>40</option>
										<option value="50" <?php if ($intRowCountPerPage == 50) echo 'selected';?>>50</option>
										<option value="60" <?php if ($intRowCountPerPage == 60) echo 'selected';?>>60</option>
										<option value="70" <?php if ($intRowCountPerPage == 70) echo 'selected';?>>70</option>
										<option value="80" <?php if ($intRowCountPerPage == 80) echo 'selected';?>>80</option>
										<option value="90" <?php if ($intRowCountPerPage == 90) echo 'selected';?>>90</option>
										<option value="100" <?php if ($intRowCountPerPage == 100) echo 'selected';?>>100</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- end pagination -->


<!--  start search result panel -->
	<div>
		<table class="orangeTable"  >
				<tr class='orangeTableHeader'>
					<th style="width: 32%">
						<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/search/' method='GET'>
							<input type='hidden' name='key_word' value='<?php if (isset($strKeyWord)) echo $strKeyWord; else echo ''?>' />
							<input type='hidden' name='category' value='<?=$intCategoryID?>' />
							<input type='hidden' name='order_by' value='name' />
							<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
							<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
							<input class='tableHeaderBtn' <?php if ($strOrderBy == 'name') echo 'style="text-decoration: underline;"'?> type='submit' value='Nosaukums' />
						</form>
					</th>
					<th style="width: 12%">
						<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/search/' method='GET'>
							<input type='hidden' name='key_word' value='<?php if (isset($strKeyWord)) echo $strKeyWord; else echo ''?>' />
							<input type='hidden' name='category' value='<?=$intCategoryID?>' />
							<input type='hidden' name='order_by' value='occ' />
							<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
							<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
							<input class='tableHeaderBtn' <?php if ($strOrderBy == 'occ') echo 'style="text-decoration: underline;"'?> type='submit' value='Sastopamība' />
						</form>
					</th>
					<th style="width: 32%">
						<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/search/' method='GET'>
							<input type='hidden' name='key_word' value='<?php if (isset($strKeyWord)) echo $strKeyWord; else echo ''?>' />
							<input type='hidden' name='category' value='<?=$intCategoryID?>' />
							<input type='hidden' name='order_by' value='def' />
							<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
							<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
							<input class='tableHeaderBtn' <?php if ($strOrderBy == 'def') echo 'style="text-decoration: underline;"'?> type='submit' value='Definīcija' />
						</form>
					</th>
					<th style="width: 12%;">
						<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/search/' method='GET'>
							<input type='hidden' name='key_word' value='<?php if (isset($strKeyWord)) echo $strKeyWord; else echo ''?>' />
							<input type='hidden' name='category' value='<?=$intCategoryID?>' />
							<input type='hidden' name='order_by' value='time' />
							<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
							<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
							<input class='tableHeaderBtn' <?php if ($strOrderBy == 'time') echo 'style="text-decoration: underline;"'?> type='submit' value='Laiks' />
						</form>
					</th>
					<th style="width: 10%; border-right-width: 0;">
						<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/search/' method='GET'>
							<input type='hidden' name='key_word' value='<?php if (isset($strKeyWord)) echo $strKeyWord; else echo ''?>' />
							<input type='hidden' name='category' value='<?=$intCategoryID?>' />
							<input type='hidden' name='order_by' value='category' />
							<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
							<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
							<input class='tableHeaderBtn' <?php if ($strOrderBy == 'category') echo 'style="text-decoration: underline;"'?> type='submit' value='Kategorija' />
						</form>
					</th>
				</tr>
				<?php foreach ($arrEntityNames as $arrEntityName):?>
				<tr class='orangeTableRow'>
					<td>
						<a href="/namedEntityDB/browse/name_documents/<?=$arrEntityName['ID']?>">
							<?=$arrEntityName['name'];?>
						</a>
					</td>
					<td><?php if ($arrEntityName['totalOccurrences'] != '') echo $arrEntityName['totalOccurrences']; else echo 0; ?></td>
					<td>
						<a href="/namedEntityDB/browse/entity/<?=$arrEntityName['entityID']?>">
							<?=$arrEntityName['definition'];?>
						</a>
					</td>
					<td><?=$arrEntityName['time'];?></td>
					<td style=" border-right-width: 0;"><?=$arrEntityName['category'];?></td>
				</tr>
				<?php endforeach;?>
		</table>
	</div>
<!-- end search result paenl -->


<!-- start pagination -->
<div>
	<table cellspacing="0" cellpadding="0" class="paginatorMainPanel">
		<tbody>
			<tr>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorLeftPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons" <?php if ($intPageNum != 1) echo "onclick=\"document.getElementById('pageNum').value='".($intPageNum-1)."'; document.forms['paginatorForm'].submit();\"" ?>>
										<div style="margin:0 -4px 0 -3px;">&lt;</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Lapa</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<input onchange="if(this.value > 0 && this.value <= <?=(ceil($intRowsCount/$intRowCountPerPage));?>) { document.getElementById('pageNum').value=this.value;} else { document.getElementById('pageNum').value=<?=(ceil($intRowsCount/$intRowCountPerPage));?>;}; document.forms['paginatorForm'].submit();" type="text" value="<?=$intPageNum;?>" />
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">no <?=(ceil($intRowsCount/$intRowCountPerPage));?></div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons" <?php if ($intPageNum != ceil($intRowsCount/$intRowCountPerPage) && $intRowsCount != 0) echo "onclick=\"document.getElementById('pageNum').value='". ($intPageNum+1) ."'; document.forms['paginatorForm'].submit();\"" ?>>
										<div style="margin:0 -4px 0 -3px;">&gt;</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorRightPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Atrasti:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="paginatorUnitCount"><?=$intRowsCount;?> rezultāti</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Rādīt lapā:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<select onchange="document.getElementById('row_count_per_page').value=this.value; document.forms['paginatorForm'].submit();">
										<option value="10" <?php if ($intRowCountPerPage == 10) echo 'selected';?>>10</option>
										<option value="20" <?php if ($intRowCountPerPage == 20) echo 'selected';?>>20</option>
										<option value="25" <?php if ($intRowCountPerPage == 25) echo 'selected';?>>25</option>
										<option value="30" <?php if ($intRowCountPerPage == 30) echo 'selected';?>>30</option>
										<option value="35" <?php if ($intRowCountPerPage == 35) echo 'selected';?>>35</option>
										<option value="40" <?php if ($intRowCountPerPage == 40) echo 'selected';?>>40</option>
										<option value="50" <?php if ($intRowCountPerPage == 50) echo 'selected';?>>50</option>
										<option value="60" <?php if ($intRowCountPerPage == 60) echo 'selected';?>>60</option>
										<option value="70" <?php if ($intRowCountPerPage == 70) echo 'selected';?>>70</option>
										<option value="80" <?php if ($intRowCountPerPage == 80) echo 'selected';?>>80</option>
										<option value="90" <?php if ($intRowCountPerPage == 90) echo 'selected';?>>90</option>
										<option value="100" <?php if ($intRowCountPerPage == 100) echo 'selected';?>>100</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- end pagination -->

<?php $this->load->view('shared/bottom');?>