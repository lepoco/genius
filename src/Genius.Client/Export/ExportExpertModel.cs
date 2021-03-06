// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Collections.Generic;

namespace Genius.Client.Export;

[Serializable]
public class ExportExpertModel
{
    public Genius.Protocol.ExpertModel System { get; set; }
    public IEnumerable<Genius.Protocol.RelationModel> Relations { get; set; }
    public IEnumerable<Genius.Protocol.ProductModel> Products { get; set; }
    public IEnumerable<Genius.Protocol.ConditionModel> Conditions { get; set; }
}
