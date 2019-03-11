<style class="cp-pen-styles">
	.itemcont .thumbnail{
	 	margin-bottom: 10px;
	 	min-height: 250px;
	}
   .itemcont h4{
    	font-weight: 600;
    	line-height: 14px;
    	padding-top: 3px;
	}
	.itemcont p{
		font-size: 12px;
		margin-top: 5px;
	}
	.itemcont .price{
		font-size: 20px;
    	margin: 0 auto;
    	color: #333;
    	margin-top: 5px;
	}

	.itemcont .uom{
		font-size: 12px;
	}

	.itemcont .seller{ font-size: 10px;display: block;margin-top: -1px;  }
	.itemcont .desc{
		min-height: 55px;
		max-height: 55px;
		font-size: 11px;
		line-height: 12px;
		overflow: hidden;
	}

	.thumbnail a>img, .thumbnail>img {
	    margin-bottom: 5px;
	    max-height: 100px;
	}

	.itemcont .thumbnail{
		opacity:0.80;
		-webkit-transition: all 0.5s; 
		transition: all 0.5s;
	}
	.itemcont .thumbnail:hover{
		opacity:1.00;
		box-shadow: 0px 0px 10px #4bc6ff;
	}
	.itemcont .line{
		margin-bottom: 5px;
		margin-top: 5px;
	}
	@media screen and (max-width: 770px) {
		.itemcont .right{
			float:left;
			width: 100%;
		}
	}
	.itemcont span.thumbnail {
        border: 1px solid #00c4ff !important;
	    border-radius: 0px !important;
	    -webkit-box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.16);
	    -moz-box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.16);
	    box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.16);
		padding: 10px;
	}
	.itemcont .container h4{margin-top:70px; margin-bottom:30px;}
	.itemcont button {    margin-top: 6px;
	}
	.itemcont .right {
	    float: right;
	    border-bottom: 2px solid #0a5971;
	}
	.itemcont .btn-info {
	    color: #fff;
	    background-color: #19b4e2;
	    border-color: #19b4e2;
		font-size:13px;
		font-weight:600;
	}
</style>
<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-12 col-sm-8 text-bold text-white padding-top-10 padding-bottom-5">Marketplace</div>
   </div>
</div>

<div class="padding-10 product-listing" style="background: #e7edf0;padding-bottom: 10px;">
  <div class="row gutter-5">
    <?php
    if (count($products)) {
    ?>
    <div class="col-xs-12">
      <div class="row gutter-5 itemcont">
      <?php foreach ($products as $item) { ?>
        <div class="col-xs-6 col-sm-3 item">
            <!-- <div class="item-box" title="<?php echo $item['Description'] ?>">
            	<div>
            		<img class="product-image" src="<?php echo public_url('assets/logo/') . logo_filename($item['Image']) ?>">
            	</div>
                <div class="product-info">
                  	<div class="text-bold product-name">
                  		<?php echo $item['Name'] ?>
                  	</div>
                  	<div class="product-price">
						<span class="text-bold text-orange small">P <?php echo number_format($item['Price']) ?></span> / 
						<span><?php echo $item['Measurement'] ?></span>
					</div>
                </div>
            </div> -->
            <span class="thumbnail" title="<?php echo $item['Description'] ?>">
      			<img src="<?php echo public_url('assets/logo/') . logo_filename($item['Image']) ?>">
      			<h4><?php echo $item['Name'] ?></h4>
      			<span class="seller text-cyan">
      				<?php echo $item['seller']['Company Name']; ?>
      			</span>
      			<p class="desc small"><?php echo $item['Description']; ?></p>
      			<hr class="line">
      			<div class="row">
      				<div class="col-sm-12">
      					<p class="price">₱<?php echo number_format($item['Price']) ?> <span class="uom"><?php echo ($item['Measurement'] ? ' / ' . $item['Measurement'] : '') ?></span></p>
      				</div>
      			</div>
    		</span>
        </div>
        <?php } ?>
      </div>
      <div class="row offset-top-10">
      	<div class="col-xs-12">
      		<?php echo $pagination ?>
      	</div>
      </div>
    </div>
    <?php
    } else {
        echo '<div class="col-xs-12"><h4 class="h4">No record found.</h4></div>';
    }
    ?>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){

  	$('.product-listing').imagesLoaded(function(){
	    $('.itemcont').isotope({
	      itemSelector : '.item'
	    });
	});

  });
</script>