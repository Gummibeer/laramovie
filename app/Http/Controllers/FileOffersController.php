<?php

namespace App\Http\Controllers;

use App\Models\DownloadedFile;
use App\Models\FileOffer;
use App\Models\OwnedMovie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;

class FileOffersController
{
    public function __invoke(Request $request): Response|JsonResponse
    {
        $format = $request->query('format', 'irc');
        $filters = new Fluent($request->input('filters', []));

        $query = FileOffer::query()
            ->select('*')
            ->where('locale', 'de')
            ->where('server_id', 1)
            // bad quality
            ->whereNot('file_name', 'ILIKE', '%.TS.%')
            ->whereNot('file_name', 'ILIKE', '%.HDTS.%')
            ->whereNot('file_name', 'ILIKE', '%.TSRip.%')
            ->whereNot('file_name', 'ILIKE', '%.MD.%')
            ->whereNot('file_name', 'ILIKE', '%.CAMv2Rip.%')
            ->whereNot('file_name', 'ILIKE', '%.TELESYNC.%')
            // bad tv shows
            ->whereNot('file_name', 'ILIKE', '%Die.Strassen.von.San.Francisco.%')
            ->whereNot('file_name', 'ILIKE', '%Kitchen.Impossible.%')
            ->whereNot('file_name', 'ILIKE', '%Ich.bin.ein.Star-Holt.mich.hier.raus.%')
            ->whereNot('file_name', 'ILIKE', '%GZSZ.%')
            ->whereNot('file_name', 'ILIKE', '%7.vs.Wild%')
            ->whereNot('file_name', 'ILIKE', '%Landarztpraxis%')
            ->whereNot('file_name', 'ILIKE', '%Spreewaldklinik%')
            ->whereNot('file_name', 'ILIKE', '%Das.Traumschiff%')
            ->whereNot('file_name', 'ILIKE', '%Der.Koenig.von.Palma%')
            // bad magazines
            ->whereNot('file_name', 'ILIKE', '%BILD.Zeitung%')
            ->whereNot('file_name', 'ILIKE', '%BILD.am.Sonntag%')
            ->whereNot('file_name', 'ILIKE', '%Spiegel.TV%')
            // unwanted
            ->whereNot('file_name', 'ILIKE', '%Microsoft.Windows%')
            ->whereNot('file_name', 'ILIKE', '%Microsoft_Windows%')
            ->whereNot('file_name', 'ILIKE', '%NWB.Datenbank%')
            ->whereNotIn('file_name', [
                'Age.of.Empires.IV.(UWP).(v5.0.7274.0.+.Online.Multiplayer).MULTI14.Interface.MULTI3.Videos.[GERMAN.ENGLISH.FRENCH]-DODI_Repack.tar',
                'IDM.Computer.Solutions.UEStudio.v2023.0.0.41.GERMAN.x64-BTCR.tar',
                'SL-Urlaubsplaner.v3.24.1.German-LAXiTY.tar',
                'WISO.Steuer.2023.v30.05.Build.3370.Portable.German.Cracked-P2P.tar',
                'EWS.Schluessel-Master.v13.02.German-LAXiTY.tar',
                'QR-Code.Creator.inkl.Logomodul.v6.3.6.0.German-LAXiTY.tar',
            ])
            // already downloaded
            ->whereNotIn('file_name', DownloadedFile::query()->select('file_name'))
            ->when(
                $filters->get('owned'),
                fn (Builder $q) => $q->where(function (Builder $query): void {
                    $query->orWhereNull('tmdb_id');
                    $query->orWhereNotIn('tmdb_id', OwnedMovie::query()->distinct()->pluck('movie_id'));
                })
            )
            ->whereNot('user', 'ILIKE', '%beast%')
            ->orderBy('bytes');

        $query->addSelect(DB::raw('CONCAT(\'/MSG \', "user", \' XDCC SEND \', file_id) as command'));

        return match ($format) {
            'json' => response()->json($query->get()->map(fn (FileOffer $offer) => Arr::only($offer->toArray(), ['file_name', 'bytes', 'command']))),
            'irc' => response($query->pluck('command')->implode(PHP_EOL), 200, [
                'Content-Type' => 'text/plain',
            ])
        };

    }
}
