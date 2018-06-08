<div id="mutation-tables">
    <?php foreach ($genes as $gene => $alias): ?>
    <fieldset>
    	
    	<a href="<?php echo site_url('gene_page/'.$gene); ?>">
        <legend id="<?php echo $gene ?>">
	        	<strong><i>
	        		<?php echo $display_names[$gene] ?>
	        	</i></strong> 	
        </legend>
        </a>

        <span>
        	<!-- Commented out the below line for PR to cordova/dev. uncomment it for PV stuff -->
        	<!-- <?php if(is_dir("assets/public/pdb/dvd-structures/$gene")){ ?><a href="<?php echo site_url('viewer/'.$gene); ?>">PV</a><?php } ?>	-->
			
			Download the variant list:
        	<a href="<?php echo gene_link_to_api($gene, 'csv', 'download') ?>">CSV</a> 
        	<a href="<?php echo gene_link_to_api($gene, 'tab', 'download') ?>">Tab</a>
	    </span>

        		         
        <div id="table-<?php echo $gene ?>" class="variant-list-container">
        </div>
    </fieldset>
    <?php endforeach; ?>
</div>
