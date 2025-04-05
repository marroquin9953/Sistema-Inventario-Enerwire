<table cellspacing="0" cellpadding="0" width="100%" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
  <tbody>
    <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="header-lg" style="border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: normal; padding: 35px 0 0; text-align: center;">
        <?= ( 'Hi,' ) ?>@if($mailForCustomer == true)<?= $userName ?>@endif
      </td>
    </tr>
    <tr>
  	<td class="mini-container-right" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 10px 14px 10px 15px; text-align: center; width: 278px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
      <tbody>
        <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="mini-block-padding" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; text-align: center;">
            <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
              <tbody>
              	@if(__ifIsset($orderUID))
                <tr style="font-family: Helvetica, Arial, sans-serif;">
                  <td class="mini-block" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center; width: 253px;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;">
                    	<?= ( 'Admin Contact for ' ) ?><?= $orderUID ?><?= __tr('Order') ?>
                    	</span>
                	
                    	<br style="font-family: Helvetica, Arial, sans-serif;"> 
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  	</td>
  </tr>
    <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 21px; padding: 10px 60px 0px; text-align: center; width: 100% !important;"> 
      	<?=  $mailText  ?>
      </td>
    </tr>
    @if($mailForAdmin == true)
		<tr style="font-family: Helvetica, Arial, sans-serif;">
			<td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 21px; padding: 20px 60px 0px; text-align: center; width: 100% !important;"> 
				<strong>By : </strong><?= $userName ?><br>
				<strong>Email : </strong><a href="mailto:<?= $senderEmail ?>"><?= $senderEmail ?></a>
			</td>
		</tr>
		<tr style="font-family: Helvetica, Arial, sans-serif;">
			<td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 21px; padding: 30px 60px 0px; text-align: center; width: 100% !important;"> 
				Regards,<br>
				<?= $userName ?>

			</td>
		</tr>
    @endif
    <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21px; padding: 10px 60px 0px; text-align: center; width: 100% !important;">   
      </td>
    </tr>
  </tbody>
</table>