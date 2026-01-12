<!DOCTYPE html>
<html>

<head>
    <title>Job Order Soft Copy</title>
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
                                <td align="center"
                                    style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#333;">
                                    JOB ORDER</td>
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
                                <td height="50"></td>
                            </tr>
                            <tr>
                                <td>


                                    <table style="border:1px solid #e2e2e2;" class="col2" width="287"
                                        border="0" align="left" cellpadding="0" cellspacing="0">


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
                                                                    Customer Name : {{ $details[0]->master_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Address : <?php echo $details[0]->address; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Telephone Number :<?php echo $details[0]->phone; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Customer TRN :<?php echo $details[0]->vat_no; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    LPO No : <?php echo $details[0]->reference_no; ?>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">

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





                                    <table width="1" height="20" border="0" cellpadding="0" cellspacing="0"
                                        align="left">
                                        <tbody>
                                            <tr>
                                                <td height="20"
                                                    style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                                    <p style="padding-left: 24px;">&nbsp;</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table style="border:1px solid #e2e2e2;" class="col2" width="287"
                                        border="0" align="right" cellpadding="0" cellspacing="0">


                                        <tbody>
                                            <tr>
                                                <td align="center">
                                                    <table class="insider" width="237" border="0"
                                                        align="center" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-family: 'Raleway', sans-serif; font-size:14px; font-weight: 300; line-height:24px; color:#0000000;">
                                                                    Vehicle Details:</td>
                                                            </tr>

                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Make :<?php echo $details[0]->make; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000;line-height:24px; font-weight: 300;">
                                                                    Model :<?php echo $details[0]->model; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Plate No :<?php echo $details[0]->reg_no; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Color : <?php echo $details[0]->color; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td
                                                                    style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                    Chasis : <?php echo $details[0]->chasis_no; ?>
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
                </td>
                </td>
            </tr>
        </tbody>
    </table>
    </td>
    </tr>

    <tr>
        <td align="center">
            <table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0"
                style="margin-left:20px; margin-right:20px;">



                <tbody>
                    <tr>
                        <td align="center">
                            <table class="col-600" width="600" border="0" align="center" cellpadding="0"
                                cellspacing="0"
                                style=" border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
                                <tbody>
                                    <tr>
                                        <td height="2"></td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table style="border:1px solid #e2e2e2;" width="598" border="0"
                                                align="center" cellpadding="0" cellspacing="0" class="col-600"
                                                style="">
                                                <tbody>
                                                    <tr>
                                                        <td align="center">
                                                            <table class="insider" width="598" border="1px"
                                                                align="center" cellpadding="1px" cellspacing="1px">

                                                                <tbody>



                                                                    <tr>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Item Code
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Description
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Unit
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Qty
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Rate
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            VAT%
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            VAT Amt.
                                                                        </th>
                                                                        <th
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Line Total
                                                                        </th>
                                                                    </tr>
                                                                    @foreach ($details as $drow)
                                                                        <tr>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->item_code; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->item_name; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->unit_name; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->quantity; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->unit_price; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->vat; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->vat_amount; ?>
                                                                            </td>
                                                                            <td
                                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                                <?php echo $drow->line_total; ?>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach




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

                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td align="center">
            <table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0"
                style="margin-left:20px; margin-right:20px;">



                <tbody>
                    <tr>
                        <td align="center">
                            <table class="col-600" width="600" border="0" align="center" cellpadding="0"
                                cellspacing="0"
                                style=" border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
                                <tbody>
                                    <tr>
                                        <td height="0.5"></td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table style="border:1px solid #e2e2e2;" width="598" border="0"
                                                align="center" cellpadding="0" cellspacing="0" class="col-600"
                                                style="">
                                                <tbody>
                                                    <tr>
                                                        <td align="center">
                                                            <table class="insider" width="598" border="1px"
                                                                align="center" cellpadding="1px" cellspacing="1px">

                                                                <tbody>



                                                                    <tr>
                                                                        <th rowspan="4" width="350"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">

                                                                        </th>
                                                                        <th
                                                                            width="140"style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Total
                                                                        </th>
                                                                        <td width="100"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            <?php echo $details[0]->total; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>

                                                                        <th width="140"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Discount
                                                                        </th>
                                                                        <td width="100"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            <?php echo $details[0]->discount; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>

                                                                        <th width="140"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            VAT Total
                                                                        </th>
                                                                        <td width="100"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            <?php echo $details[0]->vat_amount; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>

                                                                        <th width="140"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            Net Amount
                                                                        </th>
                                                                        <td width="100"
                                                                            style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                            <?php echo $details[0]->net_total; ?>
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

                                </tbody>
                            </table>

                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    @foreach ($photos as $prow)
        <tr>
            <td align="center">
                <table class="col-600" width="600" border="0" align="center" cellpadding="0"
                    cellspacing="0"
                    style="margin-left:20px; margin-right:20px; border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
                    <tbody>
                        <tr>
                            <td height="20"></td>
                        </tr>

                        <tr>
                            <td align="center"
                                style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#0000000;">
                                Vehicle Images and Details </td>
                        </tr>

                        <tr>
                            <td height="10"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>



        <tr>
            <td align="center">
                <table align="center" class="col-600" width="600" border="0" cellspacing="0"
                    cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="center" bgcolor="">
                                <table class="col-600" width="600" align="center" border="0" cellspacing="0"
                                    cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td height="33"></td>
                                        </tr>
                                        <tr>
                                            <td>


                                                <table class="col1" width="183" border="0" align="left"
                                                    cellpadding="0" cellspacing="0">

                                                    <tbody>
                                                        <tr>
                                                            <td height="18"></td>
                                                        </tr>

                                                        <tr>
                                                            <td align="center">
                                                                <img style="display:block; line-height:0px; font-size:0px; border:0px;"
                                                                    class="images_style"
                                                                    src="{{ asset('uploads/joborder/' . $prow->photo) }}"
                                                                    alt="img" width="156" height="160">

                                                            </td>




                                                        </tr>
                                                    </tbody>
                                                </table>



                                                <table class="col3_one" width="380" border="0" align="right"
                                                    cellpadding="0" cellspacing="0">

                                                    <tbody>


                                                        <tr>
                                                            <td height="5"></td>
                                                        </tr>


                                                        <tr align="left" valign="top">
                                                            <td
                                                                style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
                                                                {{ $prow->description }}
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
                                            <td height="33"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
</body>

</html>
