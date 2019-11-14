<?php include 'header.php'; ?>

<h3><span class="glyphicon glyphicon-briefcase"></span>  Data Pembinaan</h3>

<?php 
	$per_hal=20;
	$jumlah_record=mysql_query("SELECT COUNT(*) from data");
	$jum=mysql_result($jumlah_record, 0);
	$halaman=ceil($jum / $per_hal);
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$start = ($page>1) ? ($page * $per_hal) - $per_hal : 0;
?>
<div style="width: 280px;" class="col-md-10">
	<table class="col-md-12">
	<tr>
	<button style="margin-bottom:15px;width: 116px;" data-toggle="modal" data-target="#myModal" class="btn btn-info col-md-4"><span class="glyphicon glyphicon-plus"></span>Tambah Data</button>
	</tr>
		<tr>
			<td>Jumlah Record</td>		
			<td><?php echo $jum; ?></td>
		</tr>
		<tr>
			<td>Jumlah Halaman</td>	
			<td><?php echo $halaman; ?></td>
		</tr>
		<tr>
			<td>Halaman </td><td><?php echo $page; ?></td>
			
		</tr>
	</table>
</div>
<form action="#" method="POST">
	<div class="input-group col-md-3 col-md-offset-9">
		<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
		<input type="text" class="form-control" placeholder="Cari di Tanggal" aria-describedby="basic-addon1" id="caritgl" name="caritgl">
	</div>
				<script>
				   $(document).ready(function(){
					   $("#caritgl").datepicker({
						   dateFormat:'d-mm-yy',
					   })
				   })
				 </script>
	<div class="input-group col-md-3 col-md-offset-9">
		<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search"></span></span>
		<input type="text" class="form-control" placeholder="Cari Perner/Nama.." aria-describedby="basic-addon1" id="cari" name="cari">
	</div></a>
	<div class="input-group col-md-3 col-md-offset-9">
			<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-sort"></span></span>
			<select type="text" name="tindakan" placeholder="Pilih tindakan.."class="form-control">
				<option></option>
				<?php 
				$pil=mysql_query("select distinct tindakan from data ");
				while($p=mysql_fetch_array($pil)){
					?>
					<option><?php echo $p['tindakan'] ?></option>
					<?php
				}
				?>		
			</select>
		</div>
		<div class="input-group col-md-3 col-md-offset-9">
		<button style="margin-bottom:2px;margin-top:2px; width: 80px;" id="search" name="search" class="btn btn-warning">Cari</button>
		</div>
	</form>
		<div class="input-group col-md-3 col-md-offset-9">
		<span class="btn btn-warning"><a href="data.php"><span class="glyphicon glyphicon-refresh"></span></a></span>
		<?php 
				if(isset($_POST['tindakan'])){
				echo "<h> Filter by : <a style='color:black'> ". $_POST['tindakan']."</a></h>";
				}
			?>	
		</div>
<table class="table table-bordered">
	<tr>
		<th class="text-center">No</th>
		<th class="text-center">Perner</th>
		<th class="text-center">Nama Agent</th>
		<th class="text-center">Jenis Kelamin</th>
		<th class="text-center">TL</th>
		<th class="text-center">SPV</th>
		<th class="text-center">Layanan</th>
		<th class="col-md-3 text-center">Pelanggaran</th>
		<th class="col-md-4 text-center">Detail</th>
		<th class="col-md-2 text-center">Tindakan</th>
		<th class="col-md-3 text-center">Keterangan</th>
		<th class="col-md-1 text-center">Tanggal Kejadian</th>
		<th class="text-center">Opsi</th>
	</tr>
	<?php
		$npk=$_SESSION['npk'];
		$q=mysql_query("select * from user where npk='$npk'");
		while($b=mysql_fetch_array($q)){
		$namtl=$b['nama'];
	if(isset($_POST['search'])){
		$caritgl=$_POST['caritgl'];
		$caritgl= date('Y-m-d', strtotime($caritgl));
		$cari = $_POST['cari'];
		$tindakan=$_POST['tindakan'];
		$dat = mysql_query("SELECT * FROM data JOIN rules ON data.pelanggaran = rules.id_rules WHERE 
    	tglkej LIKE '%$caritgl%'
		and npk LIKE '%$cari%' 
    	and nama LIKE '%$cari%'
        and data.tindakan LIKE '%$tindakan%'
        ORDER by tglkej");
	}else{
		$dat=mysql_query("select * from data JOIN rules ON data.pelanggaran = rules.id_rules where data.tl='$namtl'order by tglkej desc limit $start, $per_hal ");
	}
	 $no =$start+1;
	while($b=mysql_fetch_array($dat)){
		$tglindo = $b['tglkej'];
		$id = $b['id'];
		?>
		<tr>
			<td class=" text-center"><?php echo $no++ ?></td>
			<td class=" text-center"><?php echo $b['npk'] ?></td>
			<td class=" text-center"><?php echo $b['nama'] ?></td>
			<td class=" text-center"><?php echo $b['jenis_kelamin'] ?></td>
			<td class=" text-center"><?php echo $b['tl'] ?></td>
			<td class=" text-center"><?php echo $b['spv'] ?></td>
			<td class=" text-center"><?php echo $b['layanan'] ?></td>
			<td class=" text-center"><?php echo $b['pelanggaran'] ?></td>
			<td class=" text-center"><?php echo $b['detail'] ?></td>
			<td class=" text-center"><?php echo $b['tindakan'] ?></td>
			<td class=" text-center"><?php echo $b['keterangan'] ?></td>
			<td class=" text-center"><?php echo $b = DATE('d - M - Y', strtotime($tglindo)) ?></td>
			<td class=" text-center">
				<a href="editdata.php?id=<?php  echo $id; ?>"class="glyphicon glyphicon-pencil" ></a>
				<a onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ location.href='hapusdata.php?id=<?php  echo $id; ?>' }" class="glyphicon glyphicon-remove"></a> 
			</td>
		</tr>		
		<?php 
			}
		}
		?>

</table>
<table class="table table-bordered">
            <tr>
                <td class="text-center"> 
                <?php
                //jika pencarian data tidak ditemukan
                if(mysql_num_rows($dat)==0){
                    echo "<font color=red><blink>Data tidak ditemukan/ Kurang Spesifik!</blink></font>";
                }
                ?>
                </td>
            </tr> 
        </table>
<ul class="pagination">			
			<?php 
			for($x=1;$x<=$halaman;$x++){
				?>
				<li><a href="?page=<?php echo $x ?>"><?php echo $x ?></a></li>
				<?php
			}
			?>						
		</ul>
		<div id="wrapper">
		<input style="display: block;width: 60px;height: 42px;"type="button" value="Top" id="tombolScrollTop" onclick="scrolltotop()">
		</div>
<!-- modal input -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" id="tbadd" onclick="add()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tambah Data </h4>
			</div>
			<div class="modal-body">
		<form action="simpan.php" method="post">
				<div class="form-group">
				<?php
					$npk=$_SESSION['npk'];
					$q=mysql_query("select * from user where npk='$npk'");
					while($b=mysql_fetch_array($q)){
				?>
					<input name="tlna" id="tlna" type="hidden"  value= "<?php echo $b['id_tl'];?>" class="form-control">
				<?php
					}
				?>
					<label>Perner</label>
					<select class="form-control"  name="npk" id="npk" onchange="ceknpk()" >
					<option value='' selected>- Pilih -</option>
						</select>
					</div>		
					<div class="form-group">
						<label>Nama</label>
						<input name="nama" type="text" readonly class="form-control"  id="nama" placeholder=" Nama">
					</div>	
					<div class="form-group">
						<label>Jenis Kelamin</label>
						<input name="jenis_kelamin" type="text" readonly class="form-control"  id="jenis_kelamin" placeholder=" Jenis Kelamin">
					</div>	
					<div class="form-group">
						<input name="id_tl" type="hidden" class="form-control"  id="id_tl" placeholder="Id tl">
					</div>	
					<div class="form-group">
						<label>Nama TL</label>
						<input name="tl" type="text" class="form-control" readonly id="tl"  placeholder="Tl">
						</select>
					</div>															
					<div class="form-group">
						<label>SPV</label>
						<input name="spv" type="text" readonly id="spv"  class="form-control" placeholder="Spv">
					</div>
					<div class="form-group">
						<label>Layanan</label>
						<input name="layanan" id="layanan" readonly type="text" class="form-control" placeholder="Layanan">
					</div>
					<div class="form-group">
						<label>Tanggal Kejadian</label>
						<input name="tglkej" type="text" class="form-control" id="tglkej" placeholder="Tanggal kejadian">
					</div>
					<div class="form-group">
					<label>Pelanggaran</label>
						<select class="form-control" onchange="cekdata()" name="pelanggaran" id="pelanggaran">
						<option value='' selected>- Pilih -</option>
						 <?php
							include "config.php";
							$pel = mysql_query("SELECT * FROM rules");
							while ($row = mysql_fetch_array($pel)) {
								echo "<option value='$row[id_rules]'>$row[pelanggaran]</option>";
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label>Tindakan</label>
						<input name="tindakan" type="text" readonly id="tindakan" class="form-control" placeholder="Tindakan">
					</div>	
					<div class="form-group">
						<label>Detail</label>
						<select name="detail" id="detail" class="form-control">
						<option selected>- Pilih  -</option>
						</select>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<input name="keterangan" type="text" class="form-control" placeholder="Keterangan">
					</div>
					<div class="form-group">
					<?php
						function getClientIP() {
							if (isset($_SERVER)) {
								if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
									return $_SERVER["HTTP_X_FORWARDED_FOR"];
								if (isset($_SERVER["HTTP_CLIENT_IP"]))
									return $_SERVER["HTTP_CLIENT_IP"];
								return $_SERVER["REMOTE_ADDR"];
							}
							if (getenv('HTTP_X_FORWARDED_FOR'))
								return getenv('HTTP_X_FORWARDED_FOR');
							if (getenv('HTTP_CLIENT_IP'))
								return getenv('HTTP_CLIENT_IP');
							return getenv('REMOTE_ADDR');
						}
						$ip= "".getClientIP()."";
					?>
						<input name="alamatip" type="hidden"  value= <?php echo $ip ;?> class="form-control" placeholder="AlamatIp">
					</div>
					<div class="modal-footer">
					<input type="reset" class="btn btn-danger" value="Reset">
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					<input type="submit" class="btn btn-primary" value="Simpan">
					</div>
			</form>
		</div>
	</div>
</div>
				
				<script>			   
					var htmlobjek;
					$(document).ready(function(){
							$("#tbadd"). add(function(){
								var tlna = $("#tlna").val();
								$.ajax({
									url: "cari_npk.php",
									data: "tlna="+tlna,
									cache: false,
									success: function(msg){
									$("#npk").html(msg);
									}
								});
							});
						});
				
				
				
					function cektl(){   
							var fromfield = $('#id_tl').val();  
							console.log(fromfield);
							$.ajax({        
								method: "POST",      
								url: "acctl.php",    
								data: { id_tl: fromfield}  
							})
								.done(function( hasilajax ){ 
									$('#tl').val(hasilajax);
									
								});
					}
					
					
					function ceknpk(){
						var npk = $("#npk").val();
						$.ajax({
							url: 'ceknpk.php',
							data:"npk="+npk ,
						}).success(function (data) {
							var json = data,
							obj = JSON.parse(json);
							$('#nama').val(obj.nama);
							$('#jenis_kelamin').val(obj.jenis_kelamin);
							$('#id_tl').val(obj.id_tl);
							$('#spv').val(obj.spv);
							$('#layanan').val(obj.layanan);
							cektl();
						});
					}
					
					var htmlobjek;
					$(document).ready(function(){
						$("#pelanggaran").change(function(){
							var pelanggaran = $("#pelanggaran").val();
							$.ajax({
								url: "cari_det.php",
								data: "pelanggaran="+pelanggaran,
								cache: false,
								success: function(msg){
									$("#detail").html(msg);
								}
							});
						});
					});

					function cekdata(){
						var pelanggaran = $("#pelanggaran").val();
							$.ajax({
							url: 'cekdata.php',
							data:"pelanggaran="+pelanggaran ,
							}).success(function (data) {
							var json = data,
							obj = JSON.parse(json);
							$('#tindakan').val(obj.tindakan);
						});
					}
				
				</script>
				<script>
				   $(document).ready(function(){
					   $("#tglkej").datepicker({
						    format: 'DD MMMM YYYY',
					   })
				   })
				   $(document).ready(function(){
					$(window).scroll(function(){
						if ($(window).scrollTop() > 100) {
							$('#tombolScrollTop').fadeIn();
						} else {
							$('#tombolScrollTop').fadeOut();
						}
					});
				});

				function scrolltotop()
				{
					$('html, body').animate({scrollTop : 0},500);
				}
				</script>
				
			
<?php 
include 'footer.php';

?>
