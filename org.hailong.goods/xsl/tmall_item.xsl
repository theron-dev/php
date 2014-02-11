<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
 		<item>
			<image><xsl:value-of select="//img[@id='J_ImgBooth']/@src"/></image>
			<title><xsl:value-of select="//input[@name='title']/@value" /></title>
			<price><xsl:value-of select="//strong[@class='J_originalPrice']/text()" /></price>
			<body><xsl:value-of select="//div[@class='tb-detail-hd']/p/text()" /></body>
			<id><xsl:value-of select="//div[@itemid]/@itemid" /></id>
		</item>
	</xsl:template>

</xsl:stylesheet>