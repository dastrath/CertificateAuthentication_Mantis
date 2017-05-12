<?php
# Copyright (c) MantisBT Team - mantisbt-dev@lists.sourceforge.net
# Licensed under the MIT license

/**
 * Certificate Auth plugin
 */
class CertificateAuthentication_MantisPlugin extends MantisPlugin  {
        /**
         * A method that populates the plugin information and minimum requirements.
         * @return void
         */
        function register() {
                $this->name = plugin_lang_get( 'title' );
                $this->description = plugin_lang_get( 'description' );
                $this->page = '';

                $this->version = '0.1';
                $this->requires = array(
                        'MantisCore' => '2.3.0',
                );

                $this->author = 'dirk astrath';
                $this->contact = 'mantis@fidocon.de';
#               $this->url = 'https://cacert.rocks';
        }

        /**
         * plugin hooks
         * @return array
         */
        function hooks() {
                $t_hooks = array(
                        'EVENT_AUTH_USER_FLAGS' => 'auth_user_flags',
                );

                return $t_hooks;
        }

        function auth_user_flags( $p_event_name, $p_args ) {
                # Don't access DB if db_is_connected() is false.
                if ($_SERVER["TLS_SUCCESS"] == 'SUCCESS') {
                        $email=explode(',',$_SERVER["TLS_DN"]);
                        $mail_adress='';
                        $count=0;
                        while ($email[$count] != "")
                        {
                                if (strpos($email[$count],'@') != 0)
                                if ($mail_adress == '')
                                        $mail_adress=explode('=',$email[$count]);
                                $count++;
                        }

                        $t_username = $mail_adress[1];

                        $c_user_id = is_blank( $t_username ) ? false : user_get_id_by_email( $t_username );
                        $e_user_id = is_blank( $t_username ) ? false : user_get_id_by_name( $p_args['username'] );

                        if ($c_user_id == $e_user_id)
                        {
                        # for everybody else use the custom authentication
                        $t_access_level = user_get_access_level( $t_user_id, ALL_PROJECTS );
                        $t_flags = new AuthFlags();

                        # Passwords managed externally for all users
                        $t_flags->setCanUseStandardLogin( true );

                        # Override Login page
                        $t_flags->setCredentialsPage( helper_url_combine( plugin_page( 'login', /* redirect */ true ), 'email=' . $t_username ) );

                        # No long term session for identity provider to be able to kick users out.
                        $t_flags->setPermSessionEnabled( false );

                        # Enable re-authentication and use more aggressive timeout.
                        $t_flags->setReauthenticationEnabled( true );
                        $t_flags->setReauthenticationLifetime( 10 );

                        $p_args['user_id'] = $t_user_id;

                        return $t_flags;
			}


                }
        }
}
                                                                                                
