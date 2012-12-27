<?php $this->load->view('shared/top');?>

<h3>Nosaukumu datu bāzes datu ieguve RDF/XML formā</h3>
	<table>
		<tr>
			<td>
				<hr />
				<table style='font-size: 11px; '>
					<tr>
						<td>Datu bāzē reģistrēto nosaukumu (name) saraksts</td>
						<td>
							<form action='/namedEntityDB/names' method="POST">
								<input type='submit' value='Skatīt' />
							</form>
						</td>
					</tr>
					<tr>
						<td>Datu bāzē reģistrēto  objektu (entity) saraksts</td>
						<td>
							<form action='/namedEntityDB/entities' method="POST">
								<input type='submit' value='Skatīt' />
							</form>
						</td>
					</tr>
					<tr>
						<td>Statistika par datu bāzes saturu</td>
						<td>
							<form action='/namedEntityDB/stats' method="POST">
								<input type='submit' value='Skatīt' />
							</form>
						</td>
					</tr>
				</table>
				<hr />
			</td>
		</tr>
		<tr>
			<td>
				<table style='font-size: 11px; '>
					<tr>
						<td>Ievadiet nosaukuma (name) identifikatoru (ID):</td>
						<td>
							<form action='/namedEntityDB/name' method='POST'>
								<input type='text' name='id' id='id' />
								<input type='submit' value='Skatīt' />
							</form>
						</td>
					</tr>
					<tr>
						<td>Ievadiet objekta (entity) identifikatoru (ID):</td>
						<td>
							<form action='/namedEntityDB/entity' method='POST'>
								<input type='text' name='id' id='id' />
								<input type='submit' value='Skatīt' />
							</form>
						</td>
					</tr>
				</table>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table style='font-size: 11px; '>
					<tr>
						<td>Ievadiet meklējamo nosaukumu vai tā fragmentu:</td>
						<td>
							<form action='/namedEntityDB/search' method='POST'>
								<input type='text' name='name' id='name' />
								<input type='submit' value='Meklēt' />
							</form>
						</td>
					</tr>
				</table>
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				<table style='font-size: 11px; '>
					<form action='/namedEntityDB/to_hist' method='POST'>
					<tr>
						<td>Ievadiet nosaukumu laika jūtīgai meklēšanai:</td>
						<td>
								<input type='text' name='name' id='name' />
								<input type='submit' value='Meklēt' />
						</td>
					</tr>
					<tr>
						<td>Ievadiet gadu, kura nosaukumus rādīt:</td>
						<td>
								<input type='text' name='year' id='year' />
						</td>
					</tr>
					</form>
				</table>
				<hr>
			</td>
		</tr>
	</table>

<?php $this->load->view('shared/bottom');?>