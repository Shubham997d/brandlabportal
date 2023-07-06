<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice pdf</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/pdf/css/style.css')}}" />
    <style type="text/css">
      @page {
      size: 818px 1145px;
      margin: 0px;
      }
      #outlook a {
      padding: 0;
      }
      body {
      width: 100% !important;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      font-family: 'Helvetica', sans-serif!important;
      }
      .ExternalClass {
      width: 100%;
      }
      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass font,
      .ExternalClass td,
      .ExternalClass div {
      line-height: 1.3;
      }
      #backgroundTable {
      margin: 0;
      padding: 0;
      width: 100% !important;
      line-height: 1.3!important;
      }
      img {
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
      }
      a img {
      border: none;
      }
      p {
      margin: 1em 0;
      }
      table td {
      border-collapse: collapse;
      }
      table {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      }
      a {
      color: #000000;
      }
      @media only screen and (max-device-width: 480px) {
      a[href^="tel"],
      a[href^="sms"] {
      text-decoration: none;
      color: black;
      pointer-events: none;
      cursor: default;
      }
      .mobile_link a[href^="tel"],
      .mobile_link a[href^="sms"] {
      text-decoration: default;
      color: #000000 !important;
      pointer-events: auto;
      cursor: default;
      }
      }
      @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
      a[href^="tel"],
      a[href^="sms"] {
      text-decoration: none;
      color: blue;
      pointer-events: none;
      cursor: default;
      }
      .mobile_link a[href^="tel"],
      .mobile_link a[href^="sms"] {
      text-decoration: default;
      color: #000000 !important;
      pointer-events: auto;
      cursor: default;
      }
      }
      @media only screen and (-webkit-min-device-pixel-ratio: 2) {
      /* Put your iPhone 4g styles in here */
      }
      @media only screen and (-webkit-device-pixel-ratio:.75) {
      /* Put CSS for low density (ldpi) Android layouts in here */
      }
      @media only screen and (-webkit-device-pixel-ratio:1) {
      /* Put CSS for medium density (mdpi) Android layouts in here */
      }
      @media only screen and (-webkit-device-pixel-ratio:1.5) {
      /* Put CSS for high density (hdpi) Android layouts in here */
      }
    </style>
    <!--[if IEMobile 7]>
    <style type="text/css">
      /* Targeting Windows Mobile */
    </style>
    <![endif]-->
    <!--[if gte mso 9]>
    <style>
      /* Target Outlook 2007 and 2010 */
    </style>
    <![endif]-->
  </head>
  <body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" id="backgroundTable" style="border-collapse: collapse;">
      <tbody>
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="650px"
              style="background-color: #ffffff; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td align="right" style="padding-top: 60px;">
                    <a href="{{URL::to('/')}}" target="_blank" style="display: inline-block; text-decoration: none;"><img width="100px" src="{{asset('/pdf/images/brandlab_logo.png')}}"
                      alt="" style="display: block;" border="0" /></a>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 50px;">
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="vertical-align: top; width: 40%;">
                            <table align="center" cellpadding="0" cellspacing="0" width="100%"
                              style="border-collapse: collapse;">
                              <tbody>
                                <tr>
                                  <td style="font-size: 36px; color: #000000;">INVOICE</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000; padding-left: 40px;">{{ $name['formInvoice']['customer_company'] }}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000; padding-left: 40px;">{{ $name['formInvoice']['customer_address'] }}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000; padding-left: 40px;">{{ $name['formInvoice']['customer_city'] }}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000; padding-left: 40px;">{{ $name['formInvoice']['postal_code'] }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td style="vertical-align: top; width: 22%; padding-left: padding-left: 15px;">
                            <table align="center" cellpadding="0" cellspacing="0" width="100%"
                              style="border-collapse: collapse;">
                              <tbody>
                                <tr>
                                  <td>
                                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                                      style="border-collapse: collapse;">
                                      <tbody>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000; font-weight: bold;">Invoice Date</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000;"><?php echo Carbon\Carbon::parse($name['formInvoice']['invoice_date'])->format('j M Y') ?></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top: 10px;">
                                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                                      style="border-collapse: collapse;">
                                      <tbody>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000; font-weight: bold;">Invoice Number</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000;"> {{ $name['formInvoice']['invoice_number'] }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top: 10px;">
                                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                                      style="border-collapse: collapse;">
                                      <tbody>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000; font-weight: bold;">Reference</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000;">{{ $name['formInvoice']['reference'] }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top: 10px;">
                                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                                      style="border-collapse: collapse;">
                                      <tbody>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000; font-weight: bold;">VAT Number</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 15px; color: #000000;">{{ $name['formInvoice']['vat_number'] }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td style="vertical-align: top; width: 28%; padding-left: 15px;">
                            <table align="center" cellpadding="0" cellspacing="0" width="100%"
                              style="border-collapse: collapse;">
                              <tbody>
                                <tr>
                                  <td style="font-size: 15px; color: #000000;">{{$address_details['content']['company_name']}}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000;">{{$address_details['content']['address']}}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000;">{{$address_details['content']['city']}}</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 15px; color: #000000;">{{$address_details['content']['postal_code']}}</td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 60px; padding-bottom: 30px;">
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="font-size: 15px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px; width: 40%; vertical-align: top;">Description</td>
                          <td style="font-size: 15px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;">Quantity</td>
                          <td style="font-size: 15px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;">Unit Price</td>
                          <td style="font-size: 15px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;">VAT</td>
                          <td style="font-size: 15px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;">Amount {{$name['formInvoice']['currency']['code']}}</td>
                        </tr>
                      <?php $subtotal = 0; ?>
                        @foreach($name['Invoice'] as $value)
                        <tr>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #dddddd; padding-left: 5px; padding-right: 5px; width: 40%; vertical-align: top;">{{ $value['description'] }}</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #dddddd; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;">{{ number_format($value['quantity'],2) }}</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #dddddd; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;">{{ number_format($value['amount'],2) }}</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #dddddd; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;">{{ $name['formInvoice']['vat_percentage']}}%</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #dddddd; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;">
                          {{ number_format($value['quantity']*$value['amount'],2) }}</td>
                        </tr>
                        <?php $subtotal +=  $value['quantity']*$value['amount'] ;?>
                        @endforeach
                        <tr>
                          <td colspan="4" style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; text-align: right; padding-left: 5px; padding-right: 5px; vertical-align: top;">Subtotal</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;">{{number_format($subtotal,2)}}</td>
                        </tr>
                        <?php $total_vat = ($name['formInvoice']['vat_percentage']/100)*$subtotal ;?>
                        <tr>
                          <td colspan="4" style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; text-align: right; padding-left: 5px; padding-right: 5px; border-bottom: 1px solid #000000; vertical-align: top;">TOTAL VAT {{ $name['formInvoice']['vat_percentage']}}%</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; border-bottom: 1px solid #000000; vertical-align: top; text-align: right;">{{number_format($total_vat,2)}}</td>
                        </tr>
                        <?php $total = $subtotal + $total_vat; ?>
                        <tr>
                          <td colspan="4" style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; text-align: right; padding-left: 5px; padding-right: 5px; font-weight: bold; vertical-align: top;">TOTAL {{$name['formInvoice']['currency']['code']}}</td>
                          <td style="font-size: 15px; color: #000000; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; font-weight: bold; vertical-align: top; text-align: right;">{{number_format($total,2)}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="font-size: 15px; color: #000000; font-weight: bold;"><span>Due Date: </span><?php echo Carbon\Carbon::parse($name['formInvoice']['due_date'])->format('j M Y') ?></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;">PAYMENT UPON RECEIPT</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 15px;">
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="font-size: 15px; color: #000000; font-weight: bold;">BANK DETAILS:</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>Bank: </span>{{$bank_detail['content']['bank']}}</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>Account Name: </span>{{$bank_detail['content']['account_name']}}</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>Sort Code: </span>{{$bank_detail['content']['sort_code']}}</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>Account Number: </span>{{$bank_detail['content']['account_number']}}</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>IBAN: </span>{{$bank_detail['content']['IBAN']}}</td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span>SWIFT Code: </span>{{$bank_detail['content']['swift_code']}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 15px; font-size: 15px; color: #000000;">BrandLab360© is a trading name of Red Storm Fashion Agency Ltd</td>
                </tr>
                <tr>
                  <td style="padding-top: 15px;">
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">Website: </span><a href="www.brandlab-360.com" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.brandlab-360.com</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">Email: </span><a href="mailto:hello@brandlab-360.com" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">hello@brandlab-360.com</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">LinkedIn: </span><a href="www.linkedin.com/company/brandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.linkedin.com/company/brandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">Facebook: </span><a href="www.facebook.com/wearebrandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.facebook.com/wearebrandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">Twitter: </span><a href="www.twitter.com/brandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.twitter.com/brandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 15px; color: #000000;"><span style="display: inline-block;">Instagram: </span><a href="www.instagram.com/brandlab.360/" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.instagram.com/brandlab.360/</a></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 15px;">
                    <table align="left" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="padding-right: 10px;"><img height="34px" src="{{asset('/pdf/images/icon-visa.png')}}"
                            alt="" style="display: block;" border="0" /></td>
                          <td style="padding-right: 10px;"><img height="34px" src="{{asset('/pdf/images/icon-mastercard.png')}}"
                            alt="" style="display: block;" border="0" /></td>
                          <td style="padding-right: 10px;"><img height="34px" src="{{asset('/pdf/images/icon-paypal.png')}}"
                            alt="" style="display: block;" border="0" /></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td><a href="www.brandlab-360.com" target="_blank" style="display: inline-block; text-decoration: underline; color: #00a1ff; font-weight: bold;">View and pay online</a></td>
                </tr>
                <tr>
                  <td style="padding-top: 60px; padding-bottom: 30px; font-size: 12px; color: #000000;">Company Registration No: 9651525.  Registered Office: Unit B3, Lakeview Business Park, Lamby Way, CARDIFF, South Glamorgan, CF3 2EP, GBR.
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>