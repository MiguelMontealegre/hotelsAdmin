@extends('mail.layout')
@section('title', 'Page Title')
@section('content')
    <table role="presentation" class="main"
           style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;"
           width="100%">
        <!-- START MAIN CONTENT AREA -->
        <tr>
            <td class="wrapper"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"
                valign="top">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                       style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                       width="100%">
                    <tr>
                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                Hi {{$user->profile->firstName}}</p>
                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                            {{ env('MENSAJE_BIENVENIDA') }}
                            <br/>
                            {{ env('BIENVENIDA_GUIA') }}
	                            <br />
	                            <br />
	                            <a href="https://lazomascotas.com:8000/api/{{$user->id}}/email-confirmation" target="_blank"
	                               style="border: solid 1px #07b59a; border-radius: 5px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 8px 12px; text-decoration: none; text-transform: capitalize; background-color: #07b59a; border-color: #07b59a; color: #ffffff;">
		                            Confirmar Correo
	                            </a>
                            </p>
                            @include('mail/sign')
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- END MAIN CONTENT AREA -->
    </table>
@endsection
