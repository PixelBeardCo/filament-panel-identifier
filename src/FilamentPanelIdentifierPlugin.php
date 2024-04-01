<?php

namespace PixelBeardCo\FilamentPanelIdentifier;

use Closure;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

class FilamentPanelIdentifierPlugin implements \Filament\Contracts\Plugin
{
    use EvaluatesClosures;

    public bool|Closure|null $visible = null;

    public bool|Closure|null $badge = null;

    public bool|Closure|null $border = null;

    public array|Closure|null $color = null;

    public string|Closure|null $title = null;

    public static function make(): static
    {
        $plugin = app(static::class);

        $plugin->color(Color::Blue);

        $plugin->badge(true);

        $plugin->border(true);

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-panel-identifier';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook('panels::user-menu.before', function () {

            if (! $this->evaluate($this->badge)) {
                return '';
            }

            return View::make('filament-panel-identifier::badge', [
                'color' => $this->getColor(),
                'title' => $this->getTitle(),
            ]);
        });

        $panel->renderHook('panels::styles.after', function () {
            if (! $this->evaluate($this->border)) {
                return '';
            }

            if (! $this->evaluate($this->title)) {
                return '';
            }

            return new HtmlString("
                <style>
                    .fi-topbar,
                    .fi-sidebar {
                        border-top: 5px solid rgb({$this->getColor()['500']}) !important;
                    }

                    .fi-topbar {
                        height: calc(4rem + 5px) !important;
                    }
                </style>
            ");
        });
    }

    public function boot(Panel $panel): void
    {
        // ...
    }

    public function badge(bool|Closure $badge = true): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function border(bool|Closure $border = true): static
    {
        $this->border = $border;

        return $this;
    }

    public function color(array|Closure $color = Color::Pink): static
    {
        $this->color = $color;

        return $this;
    }

    protected function getColor(): array
    {
        return $this->evaluate($this->color);
    }

    public function title(string $string)
    {
        $this->title = $string;

        return $this;
    }

    protected function getTitle(): string
    {
        return $this->evaluate($this->title);
    }
}
