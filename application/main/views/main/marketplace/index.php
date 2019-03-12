<style class="cp-pen-styles">
	.itemcont .thumbnail{
	 	margin-bottom: 10px;
	 	min-height: 200px;
	 	cursor: pointer;
	 	position: relative;
	}

	.itemcont .info{overflow: hidden;}
    .itemcont h4{
    	font-weight: 600;
    	line-height: 14px;
    	padding-top: 3px;
	}
	.itemcont p{font-size: 12px;margin-top: 5px;}

	.itemcont .price{
		font-size: 20px;
    	margin: 0 auto;
    	color: #333;
    	margin-top: 5px;
	}

	.itemcont .uom{font-size: 10px;}
	.itemcont .seller{ font-size: 10px;display: block;margin-top: -1px;  }
	.itemcont .desc{
		font-size: 11px;
		line-height: 12px;
		display: none;
	}

	.itemcont .bottom {
		position: absolute;
	    bottom: -5px;
	    padding: 10px 0;
	    width: calc(100% - 20px);
	}

	.itemcont .button-cont {
		position: absolute;
	    bottom: 9px;
	    right: 10px;
	}
	.itemcont .button-cont a {padding: 0 1px;}

	.itemcont .thumbnail:hover .desc{display: block;}

	.thumbnail a>img, .thumbnail>img {
	    margin-bottom: 5px;
	    max-height: 100px;
	}

	.itemcont .thumbnail{
		-webkit-transition: all 0.1s; 
		transition: all 0.1s;
	}
	.itemcont .thumbnail:hover{
		opacity:1.00;
		box-shadow: 0px 0px 20px #4bc6ff;
		border-radius: 4px !important;
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
</style>
<form id="searchProducts" action="<?php echo site_url('marketplace') ?>" method="get">
   <div class="row">
      <div class="col-md-12">
         <div class="bg-white padding-bottom-5 padding-left-5 padding-right-5">
            <div class="row gutter-5">
              <div class="col-sm-10 padding-top-5">
                <input type="text" name="search" autocomplete="off" class="form-control" placeholder="Looking for.." value="<?php echo get_post('search') ?>">
              </div>
              <div class="col-sm-2 padding-top-5">
                <button type="submit" class="btn bg-cyan text-white btn-block text-bold">Search</button>
              </div>
            </div>
         </div>
      </div>
   </div>
</form>

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
            <span class="thumbnail" data-id="<?php echo $item['id'] ?>" onclick="Marketplace.viewItem(this, event)">
      			<img title="<?php echo $item['Description'] ?>" src="<?php echo public_url('assets/logo/') . logo_filename($item['Image']) ?>">
      			<div title="<?php echo $item['Description'] ?>" class="info">
	      			<h4 class="text-blue"><?php echo $item['Name'] ?></h4>
	      			<span class="seller text-cyan">
	      				<?php echo $item['seller']['Company Name']; ?>
	      			</span>
	      			<!-- <p class="desc small"><?php echo $item['Description']; ?></p> -->
      			</div>
      			<div class="bottom">
	      			<hr class="line">
  					<p class="price">
  						<span class="text-orange">₱<?php echo number_format($item['Price']) ?></span> 
  						<span class="uom"><?php echo ($item['Measurement'] ? ' / ' . $item['Measurement'] : '') ?></span>
  					</p>
      			</div>
      			<div class="button-cont">
					<a title="Call" href="tel:<?php echo $item['seller']['sellerData']['contact']; ?>"><i class="fa fa-phone"></i></a>
					<a href="javascript:;" title="Send a message to the seller" onClick="Chatbox.openChatbox('<?php echo $item['seller']['sellerData']['mabuhayID']; ?>');"><i class="fa fa-envelope"></i></a>
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

<div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog" aria-labelledby="viewItemModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
          	<div class="col-xs-12 col-sm-4">
          		<img class="img-responsive" src="https://mgov.cloud/assets/logo/9a1c38ff84eaf53379d35ac3532ef864.jpg?1552392108">
          	</div>
          	<div class="col-xs-12 col-sm-8">
          		<div class="row">
          			<div class="col-xs-12"><span class="name h4 text-bold text-blue">Name</span></div>
          			<div class="col-xs-12"><span class="description">edfsad fasd fasd fasdf adsf asdf assdf asdf asdf asdf asdf </span></div>
          			<div class="col-xs-12 offset-top-10">
          				<span class="price text-bold text-orange">P2,312</span>
          				<span class="uom">/ piece</span>
          			</div>
          			<div class="col-xs-12 offset-top-15 small">
          				<p>Warranty: <span class="warranty text-bold">one year</span></p>
          				<p class="offset-top-5">Terms of Payment: <span class="payment-term text-bold">Installment</span></p>
          				<p class="offset-top-5">Delivery Lead Time: <span class="lead-time text-bold">Installment</span></p>
          			</div>
          			<hr>
          			<div class="col-xs-12 offset-top-15">
          				<div class="small text-bold">Seller</div>
          				<div>
          					<span class="text-cyan text-bold sellerName">E Corp</span><br>
          					<span class="small accreditation">24324334</span>
          					<div class="offset-top-10">
	          					<a class="call-bot" style="text-decoration: none;" title="Call" href="">
	          						<i class="fa fa-phone"></i> <span class="contact"></span>
	          					</a>
								<a class="chat-bot" style="text-decoration: none;" href="javascript:;" title="Send a message to the seller">
									<i class="fa fa-envelope"></i> Send a message
								</a>
							</div>
          				</div>
          			</div>
          		</div>
          	</div>
          </div>
        </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){

  	Marketplace.itemData = <?php echo json_encode($products, JSON_HEX_TAG); ?>;

  	$('.product-listing').imagesLoaded(function(){
	    $('.itemcont').isotope({
	      itemSelector : '.item'
	    });
	});

  });
</script>