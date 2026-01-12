<!DOCTYPE html>
<html>

<head>
    <title>Contract Expiry Details</title>
</head>

<body>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>

            <tr>
                <td align="center">
                    <table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td align="center" valign="top"
                                    style="background-size:cover; background-position:top;height=" 200""="">
                                    <table class="col-600" width="600" height="100" border="0" align="center"
                                        cellpadding="0" cellspacing="0">

                                        <tbody>
                                            <tr>
                                                <td align="center"
                                                    style="font-family: 'Raleway', sans-serif; font-size:26px; font-weight: bold; color:#333;">
                                                    <?php echo $company[0]->company_name; ?>
                                                    <!-- 	<img style="display:block; line-height:0px; font-size:0px; border:0px;" src="{{ asset('assets/majestic_logo.png') }}"  width="150" height="100" alt="logo"> -->
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center"
                                                    style="font-family: 'Raleway', sans-serif; font-size:16px; font-weight: bold; color:#333;">
                                                    <?php echo $company[0]->address; ?>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="col-600" width="600" height="20" border="0" align="center"
                                        cellpadding="0" cellspacing="0">

                                        <tbody>
                                            <tr>
                                                <td align="center"
                                                    style="font-family: 'Raleway', sans-serif; font-size:12px; font-weight: bold; color:#333;">
                                                    Mob:<?php echo $company[0]->phone; ?>

                                                </td>
                                                <td align="center"
                                                    style="font-family: 'Raleway', sans-serif; font-size:12px; font-weight: bold; color:#333;">
                                                    Email:<?php echo $company[0]->email; ?>

                                                </td>
                                                <td align="center"
                                                    style="font-family: 'Raleway', sans-serif; font-size:12px; font-weight: bold; color:#333;">
                                                    website:<?php echo $company[0]->website; ?>

                                                </td>
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
                <td align="center">
                    <table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0"
                        style="margin-left:20px; margin-right:20px; border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
                        <tbody>
                            <tr>
                                <td height="25"></td>
                            </tr>
                            <tr>
                                <td align="center"
                                    style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#333;">
                                    Contract Expiry Details</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>


            <tr>
                <td align="center">
                    <table width="600" class="col-600" align="center" border="0" cellspacing="0" cellpadding="0"
                        style=" border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
                        <tbody>
                            <tr>
                                <td height="25"></td>
                            </tr>
                            <tr>
                                <td>


                                    <table style="border:1px solid #e2e2e2;" class="col2" width="598"
                                        border="0" align="center" cellpadding="0" cellspacing="0">


                                        <tbody>
                                            <tr>
                                                <td align="center">
                                                    <table class="insider" width="237" border="0" align="center"
                                                        cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td height="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Customer Name : <?php echo $details->master_name; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Building Code : <?php echo $details->buildcode; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Building Name :<?php echo $details->buildname; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Flat Code :<?php echo $details->flat; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Contract No. : <?php echo $details->contract_no; ?>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Contract Expiry Date :
                                                                    {{ date('d-m-Y', strtotime($details->end_date)) }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Rent Amount :
                                                                    {{ number_format($details->rent_amount, 2) }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td height="10"></td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="30"></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
