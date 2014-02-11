<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
 		<item>
			<image><xsl:value-of select="//div[@id='preview']//img/@src"/></image>
			<title><xsl:value-of select="//div[@id='name']" /></title>
			<price><xsl:value-of select="//div[@id='fitting']" /></price>
			<id><xsl:value-of select="//span[@id='pShowSkuId']" /></id>
		</item>
	</xsl:template>

</xsl:stylesheet>