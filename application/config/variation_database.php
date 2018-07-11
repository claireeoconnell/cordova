<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PLEASE READ THROUGH EVERYTHING THOROUGHLY BEFORE EDITING!
 */


/*
 |--------------------------------------------------------------------------
 | Chunk size for Limiting Memory Load
 |--------------------------------------------------------------------------
 |
 | Dictates how large a query from the database can be at any one time.
 | This is primarily used for downloads currently. 
 | 1000 = 1000 bytes or 1 kilobyte.
 |
 */
$config['chunk_load_limit'] = 1000 * 1000; //1Mb

/*
 |--------------------------------------------------------------------------
 | mem_per_row_ratio
 |--------------------------------------------------------------------------
 |
 | Estimation of how much memory a row of data may occupy in bytes. 
 | This is used, in combination with chunk_load_limit, to chunk query 
 | results thereby limiting memory consumption by the results of queries. 
 | For example, 100 implies that 1 result row ~= 100 bytes, 
 | aka. 100bytes / 1row
 |
 */
$config['mem_per_row_ratio'] = 300;

/*
|--------------------------------------------------------------------------
| Contact Email
|--------------------------------------------------------------------------
|
| If users have questions, who should they contact?
|
*/
$config['contact_email'] = 'rmarini@healthcare.uiowa.edu';

/*
|--------------------------------------------------------------------------
| Variation Database Prefix
|--------------------------------------------------------------------------
|
| The shorthand prefix for the variation database. If the name of the
| database is the "Deafness Variation Database", then a reasonable prefix
| would be 'dvd'. The prefix is used for naming of downloadable files,
| website headers, etc.
|
*/
$config['vd_prefix'] = 'dvd'; // i.e. dvd (for Deafness Variation Database)

/*
|--------------------------------------------------------------------------
| Variation Database Version
|--------------------------------------------------------------------------
|
| Specify which version of the variation database you would like to use.
| To automatically use the latest version, set this to 0 (this is recommended
| unless you absolutely need to rollback to a previous version of the data).
|
*/
$config['vd_version'] = 0; // i.e. 0, 3, 7, etc.

/*
|--------------------------------------------------------------------------
| Variation Database Strings
|--------------------------------------------------------------------------
|
| Common strings used for views, naming of downloadable files, etc.
|
*/
$config['strings']['site_full_name']        = 'Deafness Variation Database'; // i.e Deafness Variation Database
$config['strings']['site_short_name']        = strtoupper($config['vd_prefix']); // i.e. DVD
$config['strings']['footer_info']        = '<span>&copy; 2011&ndash;'.date('Y').'</span> <a href="http://www.medicine.uiowa.edu/morl/" title="MORL Homepage">The Molecular Otolaryngology and Renal Research Laboratory</a> at <a href="http://www.uiowa.edu" title="University of Iowa Homepage">The University of Iowa</a>'; // Insert any HTML if needed 

/*
| -------------------------------------------------------------------------
| Tables
| -------------------------------------------------------------------------
|
| Database table names.
|
*/

/**
 * READ EVERYTHING ABOUT $config['tables']['vd_live'] BEFORE EDITING!!!
 *
 * Variation data that can be seen on the public website is held in the
 * live table. Prior to releasing a new version, a backup of the current 
 * live table will be made. Each version of the live table will have the 
 * following naming scheme:
 *     PREFIX + UNDERSCORE + INTEGER
 * For example: variations_1, variations_2, variations_3, etc.
 *
 * Because the versions are dynamic (and configs defined here are static),
 * the latest version will need to be determined elsewhere. Therefore, 
 * the correct table name (based on the latest version) is determined in 
 * application/core/MY_Model.php using the set_vd_live_table() function.
 * HOWEVER, if you prefer to use a specific table instead, then enter it
 * in the single quotes below.
 *
 * In short:
 *   - Set to 'variations' to automatically determine the latest table,
 *   - OR, speficy a table below.
 *
 * Unless you have a good reason to change it, it is best just to leave 
 * this alone.
 */
$config['tables']['vd_live'] = 'variations_gnomad_dev'; // READ ABOVE before changing, variations_8_1_1_dev

/**
 * Variation data that has been edited with the editor interface will
 * first be held in a queue before it is released. Specify the name of
 * the table in the database for which the queued data will be held. 
 *
 * The same rules apply to $config['tables']['vd_queue'] as they do
 * to $config['tables']['vd_live']. Read above for more info.
 * 
 * In short:
 *   - Set to 'variations_queue' to automatically determine the latest table,
 *   - OR, speficy a table below.
 *
 * Unless you have a good reason to change it, it is best just to leave 
 * this alone.
 */
$config['tables']['vd_queue'] = 'variations_queue_gnomad_dev'; // READ ABOVE before changing

/**
 * This table holds data on the number of variants in each gene.
 *
 * The same rules apply to $config['tables']['variant_count'] as they do
 * to $config['tables']['vd_live']. Read above for more info.
 * 
 * In short:
 *   - Set to 'variant_count' to automatically determine the latest table,
 *   - OR, speficy a table below.
 *
 * Unless you have a good reason to change it, it is best just to leave 
 * this alone.
 */
$config['tables']['variant_count'] = 'variant_count'; // READ ABOVE before changing

/**
 * Table of variant reviews, including private comments and whether or not
 * the variant has been confirmed for release.
 *
 * The same rules apply to $config['tables']['reviews'] as they do
 * to $config['tables']['vd_live']. Read above for more info.
 * 
 * In short:
 *   - Set to 'reviews' to automatically determine the latest table,
 *   - OR, speficy a table below.
 */
$config['tables']['reviews'] = 'reviews'; // READ ABOVE before changing

/**
 * Table of database versions.
 */
$config['tables']['versions'] = 'versions'; // i.e. 'versions'

/*
|--------------------------------------------------------------------------
| Pathogenicities
|--------------------------------------------------------------------------
|
| Specify the available pathogenicity options such as 'Pathogenic',
| 'Benign', 'Unknown significance', etc.
|
*/
$config['pathogenicities'] = array(
   'Unknown significance',
   'Benign',
   'Probable benign',
   'Predicted benign',
   'Predicted pathogenic',
   'Probable pathogenic',
   'Pathogenic',
);

/*
|--------------------------------------------------------------------------
| Minor Allele Frequencies
|--------------------------------------------------------------------------
|
| Specify which minor allele frequencies you would like to show on the
| website and import into the database. Comment out the ones you don't
| want.
|
*/
$config['frequencies'] = array(
//    'evs',         // Exome Variant Server (EVS)
//    '1000genomes', // 1000 Genomes
//    'exac',        // ExAC
    'gnomad',    //gnomad
   'otoscope',    // OtoSCOPE
);

/*
|--------------------------------------------------------------------------
| Annotation Tool Paths
|--------------------------------------------------------------------------
|
| The annotation tool used to autofill some of the fields when a variant is
| added through the editor interface. You must specify its path (either relative
| or absolute). If you use a relative path, then it must be relative to the site's
| root directory using the FCPATH constant (i.e. FCPATH/../annotation_tool/).
|
| If you are using kafeen for annotation and you installed Ruby via RVM then you may need to
| specify the absolute path to Ruby or problems may occur. You can find the full path by typing in
|     which ruby
| from the command-line. Paste the response in the single quotes below.
|
| IMPORTANT: Make sure that the entire annotation tool's directory recursively has
| read and execute permissions for group 'other' (chmod o+rx). Also make sure that the
| tmp directory has read, write and executable permissions for group 'other' (chmod o+rwx).
|
| IMPORTANT: The annotation tool is a small script, but its dependency
| files are quite large (~10GB). If you are hosting more than one variation database on
| a single server, it is recommended that this tool be placed in a common directory
| that each site can reference instead of having a copy of the tool for each site.
|
*/
$config['annotation_path'] = '/opt/kafeen/'; // i.e. /opt/annotation_tool/
$config['ruby_path'] = ''; // i.e. /usr/local/rvm/rubies/ruby-1.9.3-p484/bin/ruby

/*
|--------------------------------------------------------------------------
| API Config
|--------------------------------------------------------------------------
|
| Specify the following variables to be used with API queries.
|
*/

/**
 * Validation
 *
 * These are the allowed API parameters for 'format', 'type', and 'method'.
 * The first element is the default option. Comment out the ones you don't
 * want.
 */
$config['api']['format'] = array(
    "tab",
    "csv",
    "json",
    "xml",
    "vcf"
);
$config['api']['type'] = array(
    "position",
    "exactposition",
    "gene",
    "genelist",
    "variantlist"
);
$config['api']['method'] = array(
    "plain",
    "download"
);

/**
 * Fields
 *
 * These are the fields to select from the database when 'type' is set to 
 * any of the following:
 *   - genelist
 *   - variantlist
 */
$config['api']['genelist'] = array( // type=genelist
    'gene',
    'count',
);
$config['api']['variantlist'] = array( // type=variantlist
    'variation',
    'hgvs_protein_change',
    'hgvs_nucleotide_change',
    'variantlocale',
    'pathogenicity',
    'disease',
    'pubmed_id',
    'dbsnp',
    'gene',
    'lrt_omega',
    'phylop_score',
    'phylop_pred',
    'sift_score',
    'sift_pred',
    'polyphen2_score',
    'polyphen2_pred',
    'lrt_score',
    'lrt_pred', 
    'mutationtaster_score', 
    'mutationtaster_pred', 
    'gerp_nr',
    'gerp_rs',
    'gerp_pred',
    'otoscope_aj_ac',
    'otoscope_aj_af',
    'otoscope_co_ac',
    'otoscope_co_af',
    'otoscope_us_ac',
    'otoscope_us_af',
    'otoscope_jp_ac',
    'otoscope_jp_af',
    'otoscope_es_ac',
    'otoscope_es_af',
    'otoscope_tr_ac',
    'otoscope_tr_af',
    'otoscope_all_ac',
    'otoscope_all_af',
    'gnomad_afr_ac',
    'gnomad_afr_af',
    'gnomad_eur_ac',
    'gnomad_eur_af',
    'gnomad_amr_ac',
    'gnomad_amr_af',
    'gnomad_asn_ac',
    'gnomad_asn_af',
    'gnomad_all_ac',
    'gnomad_all_af',
    'id',
);

/*
|--------------------------------------------------------------------------
| Activity Log Path
|--------------------------------------------------------------------------
|
| The full path to the file for logging user activity. The directory that
| the log file is in must have write access for the web server.
|
| NOTE: log directories for other activity such as errors and debug 
| messages are specified in application/config/config.php instead, not here.
|
*/
$config['activity_log_path'] = APPPATH . 'logs/activity.log'; // i.e. APPPATH . 'log/activity.log'

/*
|--------------------------------------------------------------------------
| External Authentication
|--------------------------------------------------------------------------
|
| You can use external methods of authentication, which can be
| useful for cases such as logging in via University servers,
| LDAP, Google authentication, etc.
|
| To add your own, place your external authentication model in
|   application/models/external_auth/PREFIX_auth_model.php
| For example, if you are using Google authentication, then put it in
|   application/models/external_auth/google_auth_model.php
|
| In your model create a function called authenticate(). This function
| will automatically be called when you visit
|   mysite.com/auth/ext/PREFIX
|
| The model should be in charge of:
|   1) Authenticating the user via external means
|   2) Logging in the user if they're registered to use the site
|   3) Error handling (incorrect username/password, non-registered user, etc.)
|
| Enable an external method of authentication by specifying the
| following in the array below:
|   $key   = Name of the external auth model
|   $value = Title to be displayed to the user
| For example, if you have a Google auth model, enable it by adding
|   'google' => 'Log in with Google'
| Now, you can use it by going to
|   mysite.com/auth/ext/google
|
| Leave the array blank (or comment out the entries) to disable
| external authentication.
*/
$config['external_auth'] = array(
  'uiowa' => 'Log in with HawkID',
);

/* End of file variation_database.php */
/* Location: ./application/config/variation_database.php */
