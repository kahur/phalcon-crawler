<?php

namespace AA\Library\SimpleHtml\Parser;

use PHPUnit\Framework\TestCase;

class RegexParserTest extends TestCase
{
    public function testFind()
    {
        $html = "<HTML>
                <HEAD>                
                <TITLE>Your Title Here</TITLE>                
                </HEAD>                
                <BODY BGCOLOR=\"FFFFFF\">                
                <IMG SRC=\"clouds.jpg\" ALIGN=\"BOTTOM\">                
                <HR>                
                <a href=\"http://somegreatsite.com\">Link Name</a>                
                is a link to another nifty site                
                <H1>This is a Header</H1>                
                <H2>This is a Medium Header</H2>                
                Send me mail at <a href=\"mailto:support@yourcompany.com\">                
                support@yourcompany.com</a>.                
                <p> This is a new paragraph!</p>              
                <P> <B>This is a new paragraph!</B> </P>               
                <BR> <B><I>This is a new sentence without a paragraph break, in bold italics.</I></B>                
                <HR>                
                </BODY>                
                </HTML>";

        $parser = new RegexParser($html);

        $result = $parser->find('a');

        $this->assertInstanceOf(\Generator::class, $result);

        foreach ($result as $i => $element) {
            $this->assertEquals('a', $element->getName());
            if ($i === 0) {
                $this->assertEquals('http://somegreatsite.com', $element->getAttribute('href'));
                $this->assertNull($element->getAttribute('test'));
            } else {
                $this->assertEquals('mailto:support@yourcompany.com', $element->getAttribute('href'));
            }
        }

        $result = $parser->find('h1');

        foreach($result as $e) {
            $this->assertEquals('h1', $e->getName());
            $this->assertEquals([], $e->getAttributes());
        }
    }
}