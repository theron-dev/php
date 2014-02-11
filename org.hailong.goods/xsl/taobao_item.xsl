<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
 		<item>
			<image><xsl:value-of select="//img[@id='J_ImgBooth']/@data-src"/></image>
			<title><xsl:value-of select="//div[@id='detail']//h3" /></title>
			<price><xsl:value-of select="//strong[@id='J_StrPrice']" /></price>
			<body><xsl:value-of select="//div[@id='J_DivItemDesc']/*" /></body>
			<id><xsl:value-of select="//input[@name='item_id_num']/@value" /></id>
		</item>
	</xsl:template>

</xsl:stylesheet>