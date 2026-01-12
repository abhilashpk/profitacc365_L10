<?php

return array(

    'cache'    => true,
	'dateformat'    => 'F d, Y',
	'timeformat'    => 'H:i:s',
	'datetimeformat'    => 'F d, Y H:i:s',
    'per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Modules config
    |--------------------------------------------------------------------------
    */
    'modules'  => array(

		'item' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 730,
                'height' => 290
            ),
			
            'image_dir'  => '/uploads/item/',
        ),
		'employee' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/employee/',
        ),
		'psport' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 730,
                'height' => 290
            ),
			
            'image_dir'  => '/uploads/passport/',
        ),
		'visa' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 730,
                'height' => 290
            ),
			
            'image_dir'  => '/uploads/visa/',
        ),
		
		'labour' => array(

		'thumb_size' => array(
			'width'  => 150,
			'height' => 150
		),
		
		'image_size' => array(
			'width'  => 730,
			'height' => 290
		),
		
		'image_dir'  => '/uploads/labour/',
	),
	
	'health' => array(

		'thumb_size' => array(
			'width'  => 150,
			'height' => 150
		),
		
		'image_size' => array(
			'width'  => 730,
			'height' => 290
		),
		
		'image_dir'  => '/uploads/health/',
	),
	
	'idcard' => array(

		'thumb_size' => array(
			'width'  => 150,
			'height' => 150
		),
		
		'image_size' => array(
			'width'  => 730,
			'height' => 290
		),
		
		'image_dir'  => '/uploads/idcard/',
	),
	
	'medical' => array(

		'thumb_size' => array(
			'width'  => 150,
			'height' => 150
		),
		
		'image_size' => array(
			'width'  => 730,
			'height' => 290
		),
		
		'image_dir'  => '/uploads/medical/',
	),
	
		'leave' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/leave/',
	),
	
	
	'joborder' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/joborder/',
	),
	
		
	'jobestimate' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/jobestimate/',
	),
	
	
	'tenant' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/tenant/',
	),
	
	'contract' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/contract/',
	),
	
	'cargoentry' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/cargoentry/',
	),
	
	'cargodespatch' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/cargodespatch/',
	),
	
	'company' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/assets/',
	),
	'sojob' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/sojob/',
	),
	'salesinvoice' => array(

            'thumb_size' => array(
                'width'  => 150,
                'height' => 150
            ),
			
			'image_size' => array(
                'width'  => 250,
                'height' => 110
            ),
			
            'image_dir'  => '/uploads/salesinvoice/',
	),
		
		'publicfile' => array(
            'file_dir'  => '/uploads/publicfile/',
        ),
		'privatefile' => array(
            'file_dir'  => '/uploads/privatefile/',
        ),
		'api_url'		=> 'http://localhost/locstockapi/',
		
        'faq'           => array(),
        'page'          => array(),
        'video'         => array(),
        'menu'          => array(),
        'setting'       => array(),
        'user'          => array(),
        'group'         => array(),
    ),
	
	
	//role config-----------------
	/* 'roles' => array(
				1 => ['featuredProduct','featuredbrand','productRange','contentMange',
					  'users','addUser','editUser','deleteUser',
					  'category','addCategory','editCategory','deleteCategory',
					  'product','addProduct','editProduct','deleteProduct',
					  'privateFile',
					 ],
				2 => ['featuredProduct','featuredbrand','productRange','contentMange',
					  'category','addCategory','editCategory','deleteCategory',
					  'product','addProduct','editProduct','deleteProduct',
					  'privateFile',
					 ],
				3 => ['privateFile',],
			) */
	
);

