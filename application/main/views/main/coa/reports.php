<div class="table-report table-responsive bg-white padding-10">
	<h3 class="h3">Barangay Financial Report</h3>
	<h4 class="h4">Statement of Receipts And Expenditures</h4>
	<table class="table table-bordered table-condensed">
		<tr>
			<td class="text-bold">City/Municipality Code</td>
			<td style="background: #f9eec4" class="text-center"><?php echo $accountInfo->PublicOffice->Name ?? $accountInfo->citymunDesc ?></td>
			<td style="border: 0;" colspan="4"></td>
		</tr>
		<tr>
			<td class="text-bold">City/Municipality Name</td>
			<td style="background: #f9eec4" class="text-center"><?php echo $accountInfo->MunicipalityCityID ?></td>
			<td style="border: 0;" colspan="4"></td>
		</tr>
		<tr>
			<td class="text-bold">Barangay Code</td>
			<td style="background: #f9eec4" class="text-center"><?php echo $accountInfo->brgyDesc ?></td>
			<td style="border: 0;" colspan="4"></td>
		</tr>
		<tr>
			<td class="text-bold">Barangay Name</td>
			<td style="background: #f9eec4" class="text-center"><?php echo $accountInfo->BarangayID ?></td>
			<td style="border: 0;" colspan="4"></td>
		</tr>
		<tr>
			<td class="text-bold">Year</td>
			<td style="background: #f9eec4" class="text-center"><?php echo date('Y') ?></td>
			<td style="border: 0;" colspan="4"></td>
		</tr>
		<tr>
			<th></th>
			<th class="text-center">Original Budget</th>
			<th class="text-center">Final Budget</th>
			<th class="text-center">Difference Original and Final Budget</th>
			<th class="text-center">Actual Amounts</th>
			<th class="text-center">Difference Final Budget and Actual Amount</th>
		</tr>
		<tr>
			<td class="text-bold">TOTAL REVENUE (10+19+24)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">LOCAL SOURCES (11+15)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">TAX REVENUE</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Real Property Tax</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Tax on Business</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Other Taxes</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">NON-TAX REVENUE (16+17+18)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Fees and Charges</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Receipt from Economic Enterprise</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">EXTERNAL SOURCES (20+21+22+23)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Internal Revenue Allotment</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Share from Natinal Wealth</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Grants and Donations</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Subsidy</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">NON-INCOME RECIEPTS (25+27)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">CAPITAL/INVESTEMENT RECEIPTS</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Proceeds from Sale of Property, Plant and Equipment</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">RECEIPTS FROM LOAN AND BORROWING</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Borrowings</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">EXPENDITURES (31+32+33+34+35+36+37)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Personal Services</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Maintenance and Other Operating Expenditures</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Capital Outlay</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>20% Development Fund</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>10% Sanguniang Kabataan Fund</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>5% LDRRMF Fund</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="text-bold">CONTINUING APPROPRIATIONS</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Capital Outlay</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>