<?php
# Copyright (c) MantisBT Team - mantisbt-dev@lists.sourceforge.net
# Licensed under the MIT license

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'user_api.php' );

$f_username = gpc_get( 'username' );
$f_reauthenticate = gpc_get_bool( 'reauthenticate', false );
$f_return = gpc_get_string( 'return', config_get( 'default_home_page' ) );

$t_return = string_url( string_sanitize_url( $f_return ) );

if ($_SERVER["TLS_SUCCESS"] == 'SUCCESS') {
        $email=explode(',',$_SERVER["TLS_DN"]);
        $mail_adress='';
        $count=0;
        while ($email[$count] != "") {
                if (strpos($email[$count],'@') != 0)
                        if ($mail_adress == '')
                                $mail_adress=explode('=',$email[$count]);
                $count++;
        }
        $t_username = $mail_adress[1];
        $t_user_id = is_blank( $mail_adress[1] ) ? false : user_get_id_by_email( $mail_adress[1] );
}

if( $t_user_id == false ) {
        $t_query_args = array(
                'error' => 1,
                'username' => $f_username,
        );

        if( !is_blank( 'return' ) ) {
                $t_query_args['return'] = $t_return;
        }

        if( $f_reauthenticate ) {
                $t_query_args['reauthenticate'] = 1;
        }

        $t_query_text = http_build_query( $t_query_args, '', '&' );

        $t_uri = auth_login_page( $t_query_text );

        print_header_redirect( $t_uri );
}

# Let user into MantisBT
auth_login_user( $t_user_id );

# Redirect to original page user wanted to access before authentication
if( !is_blank( $t_return ) ) {
        print_header_redirect( 'login_cookie_test.php?return=' . $t_return );
}

# If no return page, redirect to default page
print_header_redirect( config_get( 'default_home_page' ) );

