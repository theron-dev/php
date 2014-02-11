<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
 		<item>
			<image><xsl:value-of select="//div[@class='gallery']//img[@src]/@src" /></image>
			<title><xsl:value-of select="//header[@id='header']//h1" /></title>
			<price><xsl:value-of select="//p[@class='orig-price']" /></price>
			<id><xsl:value-of select="//input[@name='item_num_id']/@value" /></id>
		</item>
	</xsl:template>

</xsl:stylesheet>