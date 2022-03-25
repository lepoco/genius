// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Export;
using Genius.Client.Services;
using System.Threading.Tasks;

namespace Genius.Client.Import
{
    /// <summary>
    /// Set of tools for importing.
    /// </summary>
    public static class SystemImporter
    {
        /// <summary>
        /// Tries to add data from <see cref="ExportExpertModel"/> to selected Expert System.
        /// </summary>
        public static async Task<bool> MergeSystems(GrpcExpertClientService expertClient, int systemId, ExportExpertModel expertModel)
        {
            return false;
        }
    }
}
