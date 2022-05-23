// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Client.Settings;

/// <summary>
/// Custom configuration section containing gRPC endpoints url's.
/// </summary>
public class ServicesSettings
{
    /// <summary>
    /// Genius.Core gRPC endpoint.
    /// </summary>
    public string Genius { get; set; }

    /// <summary>
    /// Genius.OAuth gRPC endpoint.
    /// </summary>
    public string OAuth { get; set; }

    /// <summary>
    /// Genius.Statistics gRPC endpoint.
    /// </summary>
    public string Statistics { get; set; }
}

