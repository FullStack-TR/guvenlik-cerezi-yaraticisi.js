<!doctype html><!-- Tanrı Türk'ü Korusun ve Yüceltsin! c* -->
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<title>Açar Oluştur</title>

		<meta name="description" content="Kriptografik açıdan güçlü olarak oluşturulan açarı güvenli çereze yazma">
		<meta name="keywords" content="güvenlik çerezi">

		<link rel="canonical" id="canonical" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "SCRIPT_NAME" ] ) ?>">

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="HandheldFriendly" content="True">

		<link rel="apple-touch-icon" sizes="180x180" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/apple-touch-icon.png?v=aTQgDJVpnf">
		<link rel="icon" type="image/png" sizes="32x32" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/favicon-32x32.png?v=aTQgDJVpnf">
		<link rel="icon" type="image/png" sizes="192x192" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/android-chrome-192x192.png?v=aTQgDJVpnf">
		<link rel="icon" type="image/png" sizes="16x16" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/favicon-16x16.png?v=aTQgDJVpnf">
		<link rel="manifest" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/site.webmanifest?v=aTQgDJVpnf">
		<link rel="mask-icon" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/safari-pinned-tab.svg?v=aTQgDJVpnf" color="#5bbad5">
		<link rel="shortcut icon" href="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/favicon.ico?v=aTQgDJVpnf">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-TileImage" content="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/mstile-144x144.png?v=aTQgDJVpnf">
		<meta name="msapplication-config" content="https://<?php echo( $_SERVER[ "SERVER_NAME" ] ); ?>/fav_icons/browserconfig.xml?v=aTQgDJVpnf">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>
		<?php
		/**
		 * 
		 * acar_oluştur
		 */

		ini_set( 'display_errors', 1 );
		error_reporting( E_ALL );

		$cerez_gecerlilik_suresi = 40; // Dakika türünden.

		if( $_SERVER[ "HTTPS" ] == "off" ){
			echo( "<h1>HTTPS Etkinleştirilmemiş.</h1>" );
			echo( "Sunucunuzun sertifika kurulumunu tamamladıktan sonra işlemlere devam edebilirsiniz.<br>\n" );
			echo( "Eğer sertifikanız zaten kuruluysa bağlantıya tıklayın:<br>\n" );
			echo( "<a href=\"https://" . $_SERVER[ "SERVER_NAME" ] . "/" . $_SERVER[ "SCRIPT_NAME" ] . "\" title=\"\">" . $_SERVER[ "SCRIPT_NAME" ] . "</a>" );
			echo( "</body>" );
			echo( "</html>" );
			die();
			/**
			 * Bağlantı güvenli olmadığından ötürü kullanıcı uyarıldı ve sayfa sonlandırıldı.
			 */
		}

		function isValid( $str ) { return !preg_match( '/[^a-z0-9]/', $str ); }

		if( isset( $_POST[ "açar" ] ) ){
			$_açar = $_POST[ "açar" ];
			if( isValid( $_açar ) && strlen( $_açar ) == 32 ){
				setcookie ( "acar", $_POST[ "açar" ], time() + 60 * $cerez_gecerlilik_suresi, "path=/;secure;HttpOnly;SameSite=Lax" ); // Çerez yazıldı.
				ob_clean();
				echo( $_POST[ "açar" ] );
			}
			die();
			// Çerez yazıldıktan sonra sayfa sonlandırıldı.
		}
        ?>
        <ol class="list-group">
            <li class="list-group-item">
                <progress id="beklemeSüresiYüzdesi" value="0" max="100"></progress>
            </li>
            <li class="list-group-item">
                <form action="https://<?php echo( $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "SCRIPT_NAME" ] ) ?>" method="POST">
                    <label for="açar">
                        <span id="kopyala" type="button" class="btn btn-danger">Açarı Kopyala:</span>
                    </label>
                    &nbsp;&nbsp;
                    <input id="açar" type="string" readonly data-etkin>
                </form>
            </li>
            <li class="list-group-item">
                Üretilen Açar Sayısı:&nbsp;&nbsp;<input id="üretilen_açar_sayısı" readonly>
            </li>
            <li class="list-group-item">
                Çerez Geçerlilik Süresi:&nbsp;&nbsp;<?php echo( $cerez_gecerlilik_suresi ) ?> dakika.
            </li>
            <li class="list-group-item">
                <div class="d-grid gap-2">
                    <button id="yeniden" type="button" class="btn btn-warning">Yeniden Başlat</button>
                </div>
            </li>
        </ol>
        <script>
            (function(){
                "use strict";

                const eKimlik = function( ö ){ return document.getElementById( ö ); }

                let 
                    sayaç = 0, 
                    intervalSüre = 200, /** ms türünden. */
                    enAzSüre = 2000, /** ms türünden */
                    geçenSüre = intervalSüre, /** ms türünden */
                    süre = 0, 
                    süreSına = () => {
                        //sayaç * intervalSüre milisaniye türünden geçen süredir ve en az geçmesi gereken süre buna eşit ya da bundan daha uzun olmalıdır.
                        geçenSüre = sayaç * intervalSüre;
                        eKimlik( "beklemeSüresiYüzdesi" ).value = ( geçenSüre * 100 / enAzSüre );
                        let basarılı = ( geçenSüre >= enAzSüre );
                        if( basarılı ){
                            eKimlik( "kopyala" ).className = "btn btn-success";
                        }
                        return basarılı ?  true : false;
                    }, 
                    tekil = function(){
                        function s(){
                            return Number( String( window.crypto.getRandomValues(  new Uint32Array( 1 ) )[ 0 ] ).substring( 0, 8 ) ).toString( 16 );
                        }
                        /**
                         * window.crypto, gerekli uzunluktan daha az değer döndürebiliyor.
                         * Bundan ötürü gerekli uzunluktan biraz daha fazlasını isterek gerekli uzunluğu .substring( 0, 8 ) ile çekerek daha kısa bir sayı sekansı dönmesini engelliyoruz.
                         */
                        return ( ( s() + s() + s() + s() + s() + s() ).substring( 0, 32 ) );
                    }, 
                    kayıtSına = async function () {
                        let _açar = eKimlik( "açar" ), 
                            _etkin = _açar.hasAttribute( "data-etkin" ) 
                        ;

                        if( !_etkin ){
                            return _açar.value;
                        }
                        /**
                         * Açar üretimi durduysa değeri döndürüyoruz.
                         * Hala açar üretiliyorsa açar üretimini durdurup sunucuya göndererek yanıt bekliyoruz.
                         */

                        açar_durdur();

                        const 
                            response = await fetch( "https://<?php echo( $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "SCRIPT_NAME" ] ) ?>", {
                                method: 'POST', // *GET, POST, PUT, DELETE, etc.
                                mode: 'no-cors', // no-cors, *cors, same-origin
                                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                                credentials: 'same-origin', // include, *same-origin, omit
                                headers: {
                                    // 'Content-Type': 'application/json'
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                redirect: 'manual', // manual, *follow, error
                                referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                                //body: JSON.stringify(data) // body data type must match "Content-Type" header
                                body: "açar=" + _açar.value
                            } ), 

                            reader = response.body.getReader(), 

                            stream = new ReadableStream({
                                start( controller ) {
                                    // Aşağıdaki işlev her veri yığınını işler
                                    function push() {
                                        // "done" bir Boole'dur ve bir "Uint8Array" değerine sahiptir
                                        reader.read().then(({ done, value }) => {
                                            // Okunacak başka veri yok mu?
                                            if (done) {
                                                // Tarayıcıya veri göndermeyi bitirdiğimizi söyleyin
                                                controller.close();
                                                return;
                                            }

                                            // Verileri alın ve denetleyici aracılığıyla tarayıcıya gönderin
                                            controller.enqueue(value);
                                            push();
                                        });
                                    };

                                    push();
                                }
                            })
                        ;

                        return new Response(stream, { headers: { "Content-Type": "text/html" } });
                    }, 
                    önbelleğeMetinKopyala = ( m ) => {
                        if( !süreSına() ){
                            alert( "Biraz daha bekleyin." );
                            return;
                        }

                        kayıtSına().then( ( d ) => {
                            try{
                                function İşleyici( o ){
                                    o.clipboardData.setData( "text/plain", m );
                                    o.preventDefault();
                                    document.removeEventListener( "kopyala", İşleyici, true );
                                }
                                document.addEventListener( "kopyala", İşleyici, true );
                                document.execCommand( "copy" );

                                açar_durdur();

                                alert( "Kopyalama başarılı.\n\nÇerez sınaması yapan dosyada ilgili yere yapıştırın.\n\n " + eKimlik( "açar" ).value );
                            } catch( h ){
                                //alert( eKimlik( "açar" ).value );
                            }
                        } );
                    }, 
                    açar_başlat = () => {
                        eKimlik( "açar" ).value = tekil();
                        sayaç++;
                        süreSına();
                        eKimlik( "üretilen_açar_sayısı" ).value = sayaç;
                    }, 
                    yenidenBaşlat = () => {
                        açar_durdur();
                        sayaç = 0;
                        geçenSüre = 0;
                        eKimlik( "kopyala" ).className = "btn btn-warning";
                        eKimlik( "üretilen_açar_sayısı" ).value = sayaç;
                        eKimlik( "açar" ).setAttribute( "data-etkin", "" );
                        window.interval_açar = setInterval( açar_başlat, intervalSüre );
                    }, 
                    açar_durdur = () => {
                        if( !window.interval_açar && typeof( window.interval_açar ) != "number" ){
                            return;
                        }
                        clearInterval( interval_açar );
                        delete window.interval_açar;
                    }, 
                    açar_ata = ( o ) => {
                        o.preventDefault();

                        eKimlik( "açar" ).removeAttribute( "data-etkin" );

                        let XHR = new XMLHttpRequest();
                        XHR.onreadystatechange = function(){
                            if( XHR.readyState == 4 && XHR.status == 200 ){
                                açar_durdur();
                                eKimlik( "açar" ).value = XHR.responseText;
                                alert( "Şimdi kopyalayın ve app_config.php dosyasında yapıştırın." );
                            }
                            if( XHR.readyState == 4 && XHR.status == 500 ){}
                        };
                        XHR.open( "POST", "https://<?php echo( $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "SCRIPT_NAME" ] ) ?>" );
                        XHR.setRequestHeader( "Content-type", "application/x-www-form-urlencoded" );
                        XHR.send(
                            "açar=" + eKimlik( "açar" ).value
                        )
                    }
                ;

                window.addEventListener( "load", () => {
                    eKimlik( "açar" ).value = "";

                    eKimlik( "yeniden" ).addEventListener( "click", yenidenBaşlat );

                    eKimlik( "açar" ).addEventListener( "click", önbelleğeMetinKopyala );

                    window.interval_açar = setInterval( açar_başlat, intervalSüre );
                } );

            })();
		</script>
        <style>
            /**
             * CSS, Eric MEYER yöntemiyle sıfırlandı.
             */
             html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{ margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline; }
            article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{ display:block; }
            body{ line-height:1.2; }
            ol,ul{ list-style:none; }
            blockquote,q{ quotes:""" """; }
            blockquote:before,blockquote:after,q:before,q:after{ content:'';content:none; }
            table{ border-collapse:collapse;border-spacing:0; }
        </style>
        <style>
            /** 
             * Bootstrap 5
             */
            *, ::after, ::before{ box-sizing: border-box; }
            :root{ scroll-behavior: smooth; }
            :root{
                --bs-blue: #0d6efd;
                --bs-indigo: #6610f2;
                --bs-purple: #6f42c1;
                --bs-pink: #d63384;
                --bs-red: #dc3545;
                --bs-orange: #fd7e14;
                --bs-yellow: #ffc107;
                --bs-green: #198754;
                --bs-teal: #20c997;
                --bs-cyan: #0dcaf0;
                --bs-white: #fff;
                --bs-gray: #6c757d;
                --bs-gray-dark: #343a40;
                --bs-primary: #0d6efd;
                --bs-secondary: #6c757d;
                --bs-success: #198754;
                --bs-info: #0dcaf0;
                --bs-warning: #ffc107;
                --bs-danger: #dc3545;
                --bs-light: #f8f9fa;
                --bs-dark: #212529;
                --bs-font-sans-serif: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                --bs-font-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
                --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            }
            body {
                margin: 0;
                font-family: var(--bs-font-sans-serif);
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: transparent;
            }

            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                margin-top: 0;
                margin-bottom: .5rem;
                font-weight: 500;
                line-height: 1.2;
            }
            .h1, h1 {
                font-size: calc(1.375rem + 1.5vw);
            }
            .h1, h1 {
                font-size: 2.5rem;
            }
            a {
                color: #0d6efd;
                text-decoration: underline;
            }

            .list-group{ display: flex;flex-direction: column;padding-left: 0;margin-bottom: 0;border-radius: .25rem; }
                .list-group-item{ position: relative;display: block;padding: .5rem 1rem;text-decoration: none;background-color: #fff;border: 0.0625rem solid rgba(0,0,0,.125); }

            progress { vertical-align: baseline; }
            label{ display: inline-block; }

            button, input, optgroup, select, textarea{ margin: 0;font-family: inherit;font-size: inherit;line-height: inherit; }

            [type="button"]:not(:disabled), [type="reset"]:not(:disabled), [type="submit"]:not(:disabled), button:not(:disabled){ cursor: pointer; }

            .btn{
                display: inline-block;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: center;
                text-decoration: none;
                vertical-align: middle;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                padding: .375rem .75rem;
                font-size: 1rem;
                border-radius: .25rem;
                transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            }

            [type="button"], [type="reset"], [type="submit"], button{ -webkit-appearance: button; }

            .btn-success{ color: #fff;background-color: #198754;border-color: #198754; }
            .btn-danger{ color: #fff;background-color: #dc3545;border-color: #dc3545; }
            .btn-warning{ color: #000;background-color: #ffc107;border-color: #ffc107; }

            .gap-2{ gap: .5rem !important; }
            .d-grid{ display: grid !important; }
        </style>
        <style>
            html { background-color: #14171a;color: #cCc; }
            body { color: #cCc;margin: 0;font-size: 1.0625rem;font-family: sans;display: flex;flex-flow: row wrap;overflow: hidden;position: fixed;height: 100%;width: 100%;background-color: inherit !important;font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"; }
                .list-group{ margin: 2.5rem auto 0.6875rem; }
                    .list-group-item { background-color: #1f252b !important; border: 0.0625rem solid rgba( 204, 204, 204, .125 ) !important; }


            #yeniden{
                cursor: pointer;
            }
            #üretilen_açar_sayısı{
                width: 83px;
            }
            #beklemeSüresiYüzdesi{
                width: 100%;
            }
        </style>
    </body>
</html>
