#!/usr/bin/perl
use strict;
use warnings;
use Carp;
use English qw( -no_match_vars );
use Cwd 'abs_path';

my $theme_name = 'iworks-base-theme';

my $root = abs_path($0);
$root =~ s/\/[^\/]+\/[^\/]+$//;

die "no root: $root\n" unless -d $root;

if ( -f $root.'/style.dev.css' ) {
    print "CSS::Minifier:: style.dev.css -> style.css\n";
    use CSS::Minifier qw(minify);
    open(COPYRIGHT, $root.'/style.dev.css') or die;
    open(INFILE, $root.'/style.dev.css') or die;
    my $copyright = '';
    for (0..10) {
        $copyright .= <COPYRIGHT> if $_;
    }

    $copyright =~ s@/\*@@g;
    $copyright =~ s@\*/@@g;

    $copyright =~ s/(Version: [\d\.]+)/$1.'.'.time/ges;

    open(OUTFILE, '>'.$root.'/style.css') or die;
    CSS::Minifier::minify(input => *INFILE, outfile => *OUTFILE, copyright => $copyright );
    close(INFILE);
    close(OUTFILE);
}
else
{
    print "no css: $root/style.dev.css\n"
}

$root .= '/scripts';

if ( -f $root.'/'.$theme_name.'.dev.js' ) {
    print "JavaScript::Minifier:: ".$theme_name.".dev.js -> ".$theme_name.".js\n";
    use JavaScript::Minifier qw(minify);
    open(INFILE, $root.'/'.$theme_name.'.dev.js') or die;
    open(OUTFILE, '>'.$root.'/'.$theme_name.'.js') or die;
    JavaScript::Minifier::minify(input => *INFILE, outfile => *OUTFILE);
    close(INFILE);
    close(OUTFILE);
}
else
{
    print "no javascript: $root/$theme_name.dev.js\n"
}
exit;

