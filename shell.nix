{ pkgs ? import <unstable> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    # PHP
    php81
    php81.packages.composer
  ];
}
