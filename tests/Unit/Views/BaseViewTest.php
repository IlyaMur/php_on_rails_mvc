<?php

declare(strict_types=1);

namespace Ilyamur\PhpOnRails\Tests\Unit\Views;

use PHPUnit\Framework\TestCase;
use Ilyamur\PhpOnRails\Views\BaseView;

class BaseViewTest extends TestCase
{
    /**
     * @dataProvider templatesProvider
     */

    public function testCorrectlyRendersTemplate(string $templateName): void
    {
        $testTemplate = file_get_contents(dirname(__DIR__) . "/__fixtures__/$templateName.txt");

        ob_start();
        BaseView::renderTemplate($templateName);
        $viewRender = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($testTemplate, $viewRender);
    }

    public function templatesProvider()
    {
        return [
            ['404'],
            ['500']
        ];
    }
}
