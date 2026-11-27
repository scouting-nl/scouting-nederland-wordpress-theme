# Font Licensing

## Bundled Fonts

The seven WOFF2 files retain their embedded copyright and license metadata.
The notices below match those records; the font binaries have not been changed.

| Files | Embedded version and copyright | License |
| --- | --- | --- |
| `poppins-300.woff2`, `poppins-400.woff2`, `poppins-500.woff2`, `poppins-600.woff2`, `poppins-700.woff2`, `poppins-900.woff2` | 4.004; Copyright 2020 The Poppins Project Authors | [SIL OFL 1.1](Poppins-OFL.txt) |
| `roboto-slab-700.woff2` | 2.002; Copyright 2018 The Roboto Slab Project Authors | [Apache 2.0](Roboto-Slab-LICENSE.txt) |

Poppins identifies [itfoundry/Poppins](https://github.com/itfoundry/Poppins) as its
source. The matching copyright notice and OFL text are also distributed by
[Google Fonts](https://github.com/google/fonts/blob/main/ofl/poppins/OFL.txt).
Roboto Slab identifies [googlefonts/robotoslab](https://github.com/googlefonts/robotoslab)
and the Apache 2.0 license in its embedded metadata. No separate upstream `NOTICE`
file was found during this audit. Preserve all bundled notices when updating fonts.

These credits and license copies do not claim a byte-identical match to the current
upstream font binaries: these files are existing webfont subsets. Do not silently
replace a font with a different version or change its license based on a newer release.

## System Fonts

Impact is proprietary. The theme uses the visitor's installed copy, with
fallbacks when unavailable. No Impact font file is bundled or downloaded.
Do not add an Impact font file here without suitable redistribution rights
compatible with the theme's distribution requirements.

For consistent rendering across devices, consider bundling a freely
redistributable alternative such as Anton, licensed under the SIL Open Font
License. Include the font's license and update the theme's font definitions
and resource credits when adding it.
