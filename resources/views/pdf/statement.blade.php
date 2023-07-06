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
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="700px"
              style="background-color: #ffffff; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td align="right" style="padding-top: 60px;">
                    <a href="{{URL::to('/')}}" target="_blank" style="display: inline-block; text-decoration: none;"><img width="250px" src="{{asset('/pdf/images/brandlab_logo.png')}}"
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
                                  <td style="font-size: 36px; color: #000000;">STATEMENT</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000; padding-left: 40px;">Ethereal London Ltd</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000; padding-left: 40px;">Kemp House</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000; padding-left: 40px;">160 City Road</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000; padding-left: 40px;">London</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000; padding-left: 40px;">EC1V 2NX</td>
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
                                          <td style="font-size: 13px; color: #000000; font-weight: bold;">As At</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 13px; color: #000000;">31 Oct 2021</td>
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
                                          <td style="font-size: 13px; color: #000000; font-weight: bold;">VAT Number</td>
                                        </tr>
                                        <tr>
                                          <td style="font-size: 13px; color: #000000;">245 7946 67</td>
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
                                  <td style="font-size: 13px; color: #000000;">Red Storm Fashion Agency T/A BrandLab360</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000;">Unit B3 Lakeview Business Park Lamby Way</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000;">Cardiff</td>
                                </tr>
                                <tr>
                                  <td style="font-size: 13px; color: #000000;">CF3 2EP</td>
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
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px; vertical-align: top;width: 13%; ">Date</td>
                          <td style="font-size: 13px; color: #000000; width:21%; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: left;">Activity</td>
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: left;width: 15%;">Reference</td>
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: left;width: 14%;">Due Date </td>
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;width: 19%;">Invoice Amount </td>
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;width: 12%;">Payments</td>
                          <td style="font-size: 13px; color: #000000; font-weight: bold; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; padding-bottom: 5px;  vertical-align: top; text-align: right;width: 16%;">Balance GBP</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px;  vertical-align: top;width: 13%; ">11 Jul 2021</td>
                          <td style="font-size: 13px; color: #000000; width: 21%;padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;">Invoice # INV-0282</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 15%; ">BrandLab M... </td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 14%; ">15 Jul 2021</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 19%; ">613.63</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 12%; ">0.00</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 16%; ">613.63</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px;  vertical-align: top;width: 13%; ">11 Aug 2021</td>
                          <td style="font-size: 13px; color: #000000; width: 21%;padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;">Invoice # INV-0302</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 15%; ">BrandLab M... </td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 14%; ">15 Aug 2021</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 19%; ">613.63</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 12%; ">0.00</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 16%; ">613.63</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px;  vertical-align: top;width: 13%; ">11 Sept 2021</td>
                          <td style="font-size: 13px; color: #000000; width: 21%;padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;">Invoice # INV-0314</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 15%; ">BrandLab M... </td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 14%; ">15 Sept 2021</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 19%; ">613.63</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 12%; ">0.00</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px;padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 16%; ">613.63</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px;  vertical-align: top;width: 13%; ">11 Oct 2021</td>
                          <td style="font-size: 13px; color: #000000; width: 21%;padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;">Invoice # INV-0325</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 15%; ">BrandLab M... </td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: left;width: 14%; ">15 Oct 2021</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 19%; ">613.63</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 12%; ">0.00</td>
                          <td style="font-size: 13px; color: #000000; padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000000; padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: right;width: 16%; ">613.63</td>
                        </tr>
                        <tr>
                          <td colspan="6" style="font-size: 20px; color: #000000; padding-top: 5px; padding-bottom: 5px; text-align: right; padding-left: 5px; padding-right: 5px; font-weight: bold; vertical-align: top;">BALANCE DUE GBP</td>
                          <td style="font-size: 20px; color: #000000; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; font-weight: bold; vertical-align: top; text-align: right;">2,454.52</td>
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
                          <td style="font-size: 13px; color: #000000;">PAYMENT UPON RECEIPT</td>
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
                          <td style="font-size: 13px; color: #000000;">BANK DETAILS:</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>Bank: </span>Barclays</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>Account Name: </span>Red Storm Fashion Agency</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>Sort Code: </span> 20-60-58</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>Account Number: </span>23036472</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>IBAN: </span>GB77 BUKB 20605823036472</td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span>SWIFT Code: </span> BUKBGB22</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 15px; font-size: 13px; color: #000000;">BrandLab360© is a trading name of Red Storm Fashion Agency Ltd</td>
                </tr>
                <tr>
                  <td style="padding-top: 15px;">
                    <table align="center" cellpadding="0" cellspacing="0" width="100%"
                      style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">Website: </span><a href="www.brandlab-360.com" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.brandlab-360.com</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">Email: </span><a href="mailto:hello@brandlab-360.com" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">hello@brandlab-360.com</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">LinkedIn: </span><a href="www.linkedin.com/company/brandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.linkedin.com/company/brandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">Facebook: </span><a href="www.facebook.com/wearebrandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.facebook.com/wearebrandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">Twitter: </span><a href="www.twitter.com/brandlab360" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.twitter.com/brandlab360</a></td>
                        </tr>
                        <tr>
                          <td style="font-size: 13px; color: #000000;"><span style="display: inline-block;">Instagram: </span><a href="www.instagram.com/brandlab.360/" target="_blank" style="display: inline-block; text-decoration: none; color: #000000;">www.instagram.com/brandlab.360/</a></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 60px; padding-bottom: 30px; font-size: 11px; color: #000000;">Company Registration No: 9651525.  Registered Office: Unit B3, Lakeview Business Park, Lamby Way, CARDIFF, South Glamorgan, CF3 2EP, GBR.
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