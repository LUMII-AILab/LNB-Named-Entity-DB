<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="" lang="">
	<head>
		<link rel="stylesheet" href="/namedEntityDB/static/css/lnb.css">
		<link rel="stylesheet" href="/namedEntityDB/static/css/header.css">
		<link rel="stylesheet" href="/namedEntityDB/static/css/headerMenu.css">
		<link rel="stylesheet" href="/namedEntityDB/static/css/content.css">
		<link rel="stylesheet" href="/namedEntityDB/static/css/paginator.css">
		<link rel="stylesheet" href="/namedEntityDB/static/css/namedEntityDbStyle.css">

		<title>LNB - Laboratorija</title>
		
	</head>
	<body>
		<div class="headerGradient"></div>
			<div align="center">
				<div id="container">
					<div id="app">
						<div>
							<div class="mainHeader">
								<div class="mainHeaderButtons">
									<a href="http://laboratorija.lndb.lv">
										<div class="headerMenu_7_lv"></div>
									</a>
									<a href="#">
										<div class="headerMenu_6_lv"></div>
									</a>
									<a href="http://webarhivs.lndb.lv">
										<div class="headerMenu_5_lv"></div>
									</a>
									<a href="#">
										<div class="headerMenu_4_lv"></div>
									</a>
									<a href="#">
										<div class="headerMenu_3_lv"></div>
									</a>
									<a href="http://kartes.lndb.lv">
										<div class="headerMenu_2_lv"></div>
									</a>
									<a href="http://periodika.lv/">
										<div class="headerMenu_1_lv"></div>
									</a>
									<a href="http://gramatas.lndb.lv/">
										<div class="headerMenu_0_lv"></div>
									</a>
								</div>
							</div>
							<div style="clear: both;"></div>
							<div style="width: 100%; height: 15px">
								<?php if ($this->session->userdata("logged_in")) :?>
								<a style="float: right; font-weight: normal; margin-right: 10px;" href="/namedEntityDB/logout">Atslēgties</a>
								<?php else: ?>
								<a style="float: right; font-weight: normal; margin-right: 10px;" href="/namedEntityDB/login">Autorizēties</a>
								<?php endif;?>
							</div>
							<div class="content">
								<div class="contentWhole">
									<div>
										<div class="contentLeft">
											<span>
												<a href="http://lvadb.lndb.lv/"> 
													<div class="logo"></div> 
												</a>
											</span>
											<div style="height: 1px; width: 175px; background-color: #dcdddf; margin: 5px 3px 5px 0px; "></div>
											<div class="searchBox"> 
												<div class="heading">Nosaukumu datu bāze</div>
												<div class="data">
													<a href="http://lvadb.lndb.lv/namedEntityDB/rdf_xml">RDF/XML</a>
													<a href="http://lvadb.lndb.lv/namedEntityDB/">Meklēšana</a>
													<?php if ($this->session->userdata("logged_in")) :?>
														<a href="/namedEntityDB/add_new_entity">Pievienot jaunu objektu</a>
													<?php endif;?>
												</div>
											</div>
											
											<div class="searchBox"> 
												<div class="heading">Teksta korpusi</div>
												<div class="data">
													<a href="http://korpuss.lnb.lv/">Korpusa meklēšana</a>
													<a href="http://korpuss.lnb.lv/bonito/run.cgi/first_form">Bonito pieeja</a>
												</div>
											</div>
											
											<div class="searchBox" style="display:none"> 
												<div class="heading">Senvārdu transliterācija</div>
												<div class="data">
													<a class="gwt-Anchor" href="http://lnb.ailab.lv:8182/normalize/core/vuschka">Transliterācijas serviss</a>
													<a class="gwt-Anchor" href="http://lnb.ailab.lv:8182/explain/ūda">Vārda skaidrojums</a>
												</div>
											</div>
											<div class="searchBox" style="display:none"> 
												<div class="heading">Morfoloģiskā analīze</div>
												<div class="data">
													<a class="gwt-Anchor" href="http://lnb.ailab.lv:8182/analyze/balta">Viena vārda analīze</a>
													<a class="gwt-Anchor" href="http://lnb.ailab.lv:8182/tokenize/balta">Analīze ar tokenizāciju</a>
												</div>
											</div>

										</div>
										<div class="contentRight">
											<form id="searchForm" action="/namedEntityDB/browse" method="GET" style="margin-top: 1px;">
												<div class="commonSearchTitle1">
													<div>LABORATORIJA</div> 
												</div>
													
												<div class="mainHeaderLinks1">
													<label id="cat_0_label" for="cat_0" onclick="changeColor(this, 0)" style="padding-left: 30px; color: rgb(167, 170, 179)">Viss</label>
													<input id="cat_0" type="checkbox" name="category" value="0" />|
													<label id="cat_1_label" for="cat_1" onclick="changeColor(this, 1)" style="color: rgb(167, 170, 179)">Persona</label>
													<input id="cat_1" type="checkbox" name="category" value="1" />|
													<label id="cat_2_label" for="cat_2" onclick="changeColor(this, 2)" style="color: rgb(167, 170, 179)">Vieta</label>
													<input id="cat_2" type="checkbox" name="category" value="2" />|
													<label id="cat_3_label" for="cat_3" onclick="changeColor(this, 3)" style="color: rgb(167, 170, 179)">Organizācija</label>
													<input id="cat_3" type="checkbox" name="category" value="3" />
												</div>
												
												<div class="mainHeaderSearch1">
													<div>
														<div>Atslēgvārds:</div>
														<input id="inpName" name="key_word" value="<?php if (isset($strKeyWord)) echo $strKeyWord;?>" />
														<input id="inpBtn" type="submit" value="" />
													</div>
												</div>
											</form>
											
											<script type="text/javascript">
													<?php 
													if (isset($intCategoryID))
													{
													 ?>
													 	var chb = document.getElementById("cat_" +<?=$intCategoryID?>);
													 	chb.checked = true;
													 	var label = document.getElementById("cat_" +<?=$intCategoryID?> + "_label");
													 	label.style.color = "#4C5666";
													 <?php 
													 }
													 ?>
											</script>
													
											<script type="text/javascript">
												function changeColor(obj, id)
												{
													var el;
													el = document.getElementById('cat_' + id);
									
													obj.style.color = '#4C5666';
													el.checked = true;
													for(var i=0; i < 9; i++)
													{
														if (i != id)
														{
															el = document.getElementById('cat_' + i);
															el.checked = false;
															obj = document.getElementById('cat_' + i + '_label');
															obj.style.color = 'rgb(167, 170, 179)';
														}
													}
													document.forms["searchForm"].submit();
												}
											</script>
			
											<div style="height: 1px; width: 800px; background-color: #dcdddf; margin: 5px 3px 5px 0px; "></div>
